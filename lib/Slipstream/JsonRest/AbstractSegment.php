<?php
namespace Slipstream\JsonRest;

class AbstractSegment implements Segment{
	protected $_logHandler;
	protected $_injector;
	protected $_parser=array();

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