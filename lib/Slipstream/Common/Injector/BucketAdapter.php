<?php
namespace Slipstream\Common\Injector;

/**
 * @author jaanush
 *
 */
class BucketAdapter extends \k_InjectorAdapter{

	/**
	 * @var unknown_type
	 */
	/*
	protected function instantiate($className) {
		$obj=$this->_injector->create($className);
		if (isset($this->eventManager) & method_exists($obj, 'setEventManager')) {
			$obj->setEventManager($this->eventManager);
		}
		if (method_exists($obj, 'setInjector')) {
			$obj->setInjector($this);
		}
		return $obj;
	}

	public function get($className){
		return $this->_injector->get($className);
	}

	public function create($className){
		return $this->_injector->create($className);
	}

	public function set($obj,$className=null){
		return $this->_injector->set($obj,$className);
	}

	public function register($interface, $className){
		return $this->_injector->registerImplementation($interface, $className);
	}
	*/
}