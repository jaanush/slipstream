<?php
namespace Slipstream\Common\Data\Driver;

class DataObjDriver extends AbstractDriver{
	protected $data=array();
	protected $isDirty=false;
	protected $source=null;
	protected $name;
	protected $metadata;
	protected $primary='id';
	protected $indexSource=array();
	protected $index=array();

	public function __construct($source=null){
		$this->load($source);
	}

	public function setMetadata($name,$data){
		$this->name=$name;
		$this->metadata=$data;
	}

	public function getSchema(){
		return $this->metadata['schema'];
	}

	public function getIdFromData($data){
		return $data[$this->getIdAttr()];
	}

	public function getIdAttr(){
		return $this->metadata['schema']['idAttribute'];
	}

	public function __destruct(){
		$this->save();
	}

	public function load($source=null){
		if($source) $this->source=$source;
		if(file_exists($this->source)) $this->data=unserialize(file_get_contents($this->source));
		foreach($this->indexSource as $_key=>$file) {
			if(file_exists($file)) {
				$this->index[$_key]=unserialize(file_get_contents($file));
			} else {
				$this->rebuildIndex();
				return;
			}
		}
	}

	public function save(){
		if($this->isDirty && $this->source) {
			file_put_contents($this->source,serialize($this->data));
			foreach($this->index as $_key=>$_index){
				file_put_contents($this->indexSource[$_key],serialize($_index));
			}
		}
	}

	public function setData(array $data){
		$this->data=$data;
		$this->rebuildIndex();
	}

	public function find($id){
		return $this->fetchById($id);
	}

	public function findByKey($key,$val){
		if($key===$this->primary){
			return $this->fetchById($val);
		} elseif(array_key_exists($key,$this->index)){
			return $this->fetchById($this->index[$key][$val]);
		}
		throw new DriverException('findByKey fail: No such index: '.$key);
	}

	public function getAll(){
		return array_values($this->data);
	}

	public function fetchById($id,$partial=false){
		$_result=array();
		if(!is_array($id)) $id=array($id);
		foreach($id as $key){
			if(array_key_exists($key,$this->data)) {
				$_result[]=$this->data[$key];
			} else {
				throw new DriverException('fetchById fail: No such ID: '.$key);
			}
		}
		return (count($_result)==1?$_result[0]:$_result);
		//throw new \Exception();
		//throw new \ErrorException('fetchById faile: No such ID');
	}

	public function set($id,$data=array()){
		if(is_array($id)){
			$data=$id;
			$id=$data[$this->getIdAttr()];
		}
		$this->isDirty=true;
		$this->removeFromIndex($data);
		$this->addToIndex($data);
		return $this->data[$id]=$data;
	}

	public function add($data){
		//$_key=array_key_existsts($this->primary,$this->data)?max(array_keys($this->data))+1:1;
		$_key=empty($this->data)?1:max(array_keys($this->data))+1;
		$data[$this->primary]=$_key;
		$this->data[$_key]=$data;
		$this->isDirty=true;
		$this->addToIndex($data);
		return $data;
	}

	protected function removeFromIndex($data){
		$_indexes=array_intersect(array_keys($data),array_keys($this->indexSource));
		foreach($_indexes as $key){
			if(is_array($this->index[$key]) && array_key_exists($data[$key],$this->index[$key])){
				if(is_array($this->index[$key][$data[$key]])){
					unset($this->index[$key][$data[$key]][array_search($data[$this->primary])]);
				} else {
					unset($this->index[$key][$data[$key]]);
				}
			}
		}
	}

	protected function addToIndex($data){
		$_indexes=array_intersect(array_keys($data),array_keys($this->indexSource));
		foreach($_indexes as $key){
			if(!is_array($this->index[$key])) $this->index[$key]=array();
			if(array_key_exists($data[$key],$this->index[$key])){
				if(!is_array($this->index[$key][$data[$key]])) $this->index[$key][$data[$key]]=array($this->index[$key][$data[$key]]);
				$this->index[$key][$data[$key]][]=$data[$this->primary];
			} else {
				$this->index[$key][$data[$key]]=$data[$this->primary];
			}
		}
	}

	public function rebuildIndex(){
		$this->index=array();
		foreach($this->data as $row){
			$this->addToIndex($row);
		}
	}

	public function query($query,$partial=false){

	}

	public function get($id=null){
		if(empty($id)) return $this->getAll();
		return $this->fetchById($id);
	}

	public function put($id,$data){
		return $this->set($id,json_decode($data,true));
	}

	public function post($id,$data){
		return $this->add(json_decode($data,true));
	}

	public function delete($id){
		$this->removeFromIndex($this->data[$id]);
		unset($this->data[$id]);
	}
}
