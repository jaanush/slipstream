<?php
namespace Slipstream\JsonRest\JsonPath;

class Parser extends \Slipstream\JsonRest\AbstractParser{
	protected $_root;

	public function parse($query,$body=''){
		$_query=trim($query,'?');
		$this->_root=$this->injector()->create('Slipstream\JsonRest\JsonPath\Root');
		$this->_root->parse($_query);
	}

	public function parseData($data){
		return $this->_root->parseData($data);
		return $data;
	}
}