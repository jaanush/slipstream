<?php
namespace Slipstream\Common\Injector;

abstract class AbstractInjector implements Injector{

	/**
	 * @var unknown_type
	 */
	protected $eventManager;
	protected $aliases=array();

	public function setImplementation($class_name, $implementing_class) {
		$this->aliases[strtolower($class_name)] = $implementing_class;
	}

	public function createComponent($class_name,$context) {
		$this->logHandler->log($class_name,'Creating component');
		$component = $this->instantiate($class_name);
		//$component = $this->instantiate(isset($this->aliases[strtolower($class_name)]) ? $this->aliases[strtolower($class_name)] : $class_name);
		$component->setContext($context);
		$component->setLogHandler($this->logHandler);
   		//$component->setInjector($this);
		return $component;
	}

	public function setEventManager(\Slipstream\Common\Event\Manager $eventManager){
		$this->eventManager=$eventManager;
	}
	public function setLogHandler(\Slipstream\Common\Log\Handler $logHandler){
		$this->logHandler=$logHandler;
	}
}