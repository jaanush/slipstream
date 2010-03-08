<?php
namespace Slipstream\JsonRest\Component;

class AbstractComponent extends \k_Component{
	protected $_log;

	public function __construct(\Slipstream\Common\Log\Handler $log){
		$this->_log=$log;
		$this->log()->log(get_class($this),'Initiating');
	}

	protected function log(){
		return $this->_log;
	}
}