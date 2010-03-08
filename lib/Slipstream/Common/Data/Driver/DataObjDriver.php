<?php
namespace Slipstream\Common\Data\Driver;

class DataObjDriver extends AbstractDriver{
	protected $data=array();
	protected $isDirty=false;
	protected $source=null;
	protected $name;
	protected $metadata;

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
	}

	public function save(){
		if($this->isDirty && $this->source) file_put_contents($this->source,serialize($this->data));
	}

	public function setData(array $data){
		$this->data=$data;
	}

	public function find($id){
		return $this->fetchById($id);
	}

	public function getAll(){
		return array_values($this->data);
	}

	public function fetchById($id,$partial=false){
		if(array_key_exists($id,$this->data)) return $this->data[$id];
		//throw new \Exception();
		//throw new \ErrorException('fetchById faile: No such ID');
		throw new DriverException('fetchById fail: No such ID');
	}

	public function set($id,$data=array()){
		if(is_array($id)){
			$data=$id;
			$id=$data[$this->getIdAttr()];
		}
		$this->isDirty=true;
		return $this->data[$id]=$data;
	}

	public function add($data){
		$_key=empty($this->data)?1:max(array_keys($this->data))+1;
		$data['id']=$_key;
		$this->data[$_key]=$data;
		$this->isDirty=true;
		return $data;
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
		unset($this->data[$id]);
	}
}
