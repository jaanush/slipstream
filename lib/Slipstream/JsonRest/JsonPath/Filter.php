<?php
namespace Slipstream\JsonRest\JsonPath;

class Filter extends \Slipstream\JsonRest\AbstractSegment{
	protected $_attribute;
	protected $_operator;
	protected $_value;

	public function parse($segment){
		$this->_attribute=$segment[1];
		$this->_operator=$segment[2];
		$this->_value=$segment[3];
	}

	public function parseData($data){
		$_attr=$this->_attribute;
		$_op=$this->_operator;
		$_val=$this->_value;
		if($this->_operator!=='=') return $data;
		$this->log()->log(substr($this->_value,0,1).'|'.substr($this->_value,-1,1));
		if($this->_value=='*'){
			$_filter=function($val) use ($_attr,$_op,$_val){
				return array_key_exists($_attr,$val);
			};
		}
		$_result=array_filter($data,$_filter);
		$this->log()->log($_result,'Result');
		return $_result;
		/*
		foreach($this->_parser as $_obj){
			array_filter($data,function($val) use ($this->_attribute,$this->_operator,$this->_value){

			});
		}
		*/
	}
}