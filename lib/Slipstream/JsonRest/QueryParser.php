<?php
namespace Slipstream\JsonRest;

class QueryParser{
	protected $_configuration;

	public function __construct(
		\Slipstream\Common\ConfigurationInterface $conf){
		$this->_configuration=$conf;
	}

	public function parse($query,$body=''){

	}
}