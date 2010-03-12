<?php
namespace Slipstream\JsonRest\JsonPath;

class Root extends \Slipstream\JsonRest\AbstractSegment{
	public function parse($query,$body=''){
		$_query=trim($query,'?');
		$_constructor=$this->delegate($this->split($_query));
		return $_constructor;
	}

	protected function split($query){
		return explode('&',$query);
	}

	protected function delegate($query){
		if(!is_array($query)) $query=array($query);
		foreach($query as $_pos=>$_part){
			$this->_parser[]=$this->branch($_part);
		}
	}

	public function parseData($data){
		foreach($this->_parser as $_obj){
			$data=$_obj->parseData($data);
		}
		$this->log()->log($data,'Data');
		return $data;
	}

	protected function branch($part,$matches=array()){
		if(preg_match('/^(\w+)([\<|\>|=])(.+)$/',$part,$matches)){
			$_class='Filter';
		} elseif(preg_match('/^(\w+)\(([\+|-]?)(.*)\)$/',$part,$matches)){
			$_class=ucfirst($matches[1]);
		}
		$_obj=$this->injector()->create('Slipstream\JsonRest\JsonPath\\'.$_class);
		$_obj->parse($matches);
		return $_obj;
	}
}