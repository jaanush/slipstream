<?php
namespace Slipstream\JsonRest\JsonPath;

class Sort extends \Slipstream\JsonRest\AbstractSegment{
	protected $_descending=true;
	protected $_attribute;

	public function parse($segment){
		if($segment[2]==='+') $this->_descending=false;
		$this->_attribute=$segment[3];
	}

	public function parseData($data){
		$_attr=$this->_attribute;
		$_log=$this->log();
		usort($data,function($val1,$val2) use($_attr,$_log){
			$_c1=isset($val1[$_attr]);
			$_c2=isset($val2[$_attr]);
			if($_c1 && $_c2){
				//$_log->log(1,'both '.$val1['id'].'-'.$val2['id']);
				if(is_numeric($val1[$_attr]) && is_numeric($val2[$_attr])) return ($val1[$_attr]===$val2[$_attr]?0:($val1[$_attr]<$val2[$_attr]?-1:1));
				$_log->log('Sring compare');
				return strcmp($val1[$_attr],$val2[$_attr]);
			} elseif($_c1 or $_c2){
				//$_log->log(($_c1?1:-1),'one '.$val1['id'].'-'.$val2['id']);
				return ($_c1?-1:1);
			}
			//$_log->log(-1,'none '.$val1['id'].'-'.$val2['id']);
			return -1;
		});
		return $data;
	}
}