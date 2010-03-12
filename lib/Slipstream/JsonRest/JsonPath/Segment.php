<?php
namespace Slipstream\JsonRest\JsonPath;

class Segment extends \Slipstream\JsonRest\AbstractSegment{

	public function parse($query,$body){
		$_query=trim($query,'?');
		$_constructor=$this->delegate($this->split($_query));
	}

	protected function split($query){
		return explode('&',$query);
	}

	protected function delegate($part){
		if(!is_array($query)) $query=array($query);
		foreach($query as $_pos=>$_part){
			$this->match($_part);
		}
	}

	protected function match($part,$matches=array()){
		preg_match('/(\w)\((.*)\)/',$part,$matches);
		$this->log()->log($matches,'Matches');
	}
}