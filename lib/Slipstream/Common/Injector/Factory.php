<?php
namespace Slipstream\Common\Injector;

class Factory{
	protected $injector;
	protected $entityManager;

	public function new_Slipstream_Common_Log_Handler(){
		$_obj=new \Slipstream\Common\Log\Handler($this->injector->get('Slipstream\Common\ConfigurationInterface'),$this->injector);
		$_obj->setEventManager($this->injector->get('Slipstream\Common\Event\Manager'));
		$_obj->log('Event handler started');
		return $_obj;
	}

	public function new_bucket_Container(){
		return $this->injector;
	}

	public function setInjector($injector){
		$this->injector=$injector;
	}

	public function setEntityManager($entityManager){
		$this->entityManager=$entityManager;
	}
}