<?php
namespace Slipstream\Common;

class Configuration implements ConfigurationInterface{
	protected $_attributes=array();

	public function __construct(){
		$this->_attributes=array(
			'throwErrorExceptions'=>false,
			'throwAssertionExceptions'=>false,
			'convertAssertionErrorsToExceptions'=>true,
			'logOutput'=>array('Slipstream\Common\Log\Output\FirePHPLogger','Slipstream\Common\Log\Output\FileLogger'),
			'fileLoggerPath'=>dirname(dirname(dirname(__DIR__))).'/debug.log',
			'domainDefinition'=>array()
		);
	}

	public function __call($func,$val=null){
		$com=substr($func,0,3);
		$prop=lcfirst(substr($func,3));
		if(array_key_exists($prop, $this->_attributes)) {
			if($com=='set'){
				$attr=$val[0];
				$this->_attributes[$prop]=$attr;
				return true;
			} elseif($com=='get') {
				return $this->_attributes[$prop];
			}
		}
		throw new \ErrorException('Undefined property via __get(): ' . $func." ({$com},{$prop})",null,E_USER_NOTICE);
		/*$trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;*/
	}
}