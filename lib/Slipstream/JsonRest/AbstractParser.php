<?php
namespace Slipstream\JsonRest;

class AbstractParser implements Parser{
	protected $_logHandler;
	protected $_injector;

	public function __construct(
		\Slipstream\Common\Log\Handler $logHandler,
		\Slipstream\Common\Injector\Injector $injector){
		$this->_logHandler=$logHandler;
		$this->_injector=$injector;
	}

	protected function log(){
		return $this->_logHandler;
	}

	protected function injector(){
		return $this->_injector;
	}
}