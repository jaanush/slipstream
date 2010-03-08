<?php
namespace Slipstream\Common;

class DomainObjectManager{
	private $_entityManager;
	private $_logHandler;
	private $_eventManager;
	private $_domainObjectIndex;

	public function __construct(
		\Slipstream\Common\ConfigurationInterface $configuration,
		\Slipstream\Common\Event\Manager $eventManager,
		\Slipstream\Common\Log\Handler $logHandler,
		\Slipstream\Common\Injector\Injector $injector
		){
		$this->setEventManager($eventManager);
		$this->setLogHandler($logHandler);
		$this->setConfiguration($configuration);
		$this->_injector=$injector;
	}

	public function setDomainObjectIndex(array $index){
		$this->_domainObjectIndex=$index;
	}

	public function setConfiguration(\Slipstream\Common\ConfigurationInterface $conf){
		$this->_configuration=$conf;
		$this->setDomainObjectIndex($conf->getDomainDefinition());
		return $this;
	}

	public function setEntityManager(\Doctrine\ORM\EntityManager $em){
		$this->_entityManager=$em;
		return $this;
	}

	public function setLogHandler(\Slipstream\Common\Log\Handler $logHandler) {
		$this->_logHandler = $logHandler;
		return $this;
	}
	public function setEventManager(\Slipstream\Common\Event\Manager $eventManager){
		$this->_eventManager=$eventManager;
		return $this;
	}

	public function domain($name){
		if(array_key_exists($name,$this->_domainObjectIndex)) {
			return call_user_func(array($this,'get'.ucfirst($this->_domainObjectIndex[$name]['type']).'Domain'),$name);
		}
	}

	public function domainExists($name){
		return array_key_exists($name,$this->_domainObjectIndex);
	}

	protected function getDoctrineDomain($name){
		if(isset($this->_domainObjectIndex[$name])){
			if(array_key_exists('entity',$this->_domainObjectIndex[$name]) && is_object($_obj=$this->_domainObjectIndex[$name]['entity'])) {
				return $_obj;
			} else if($_em=$this->getEntityManager()){
				$_rep=$_em->getRepository($this->_domainObjectIndex[$name]['class']);
				$this->_domainObjectIndex[$name]['entity']=$_rep;
				return $_rep;
			}
		}
		throw new UndefinedDomainException('Unable to locate domain: '.$name);

	}

	protected function getDataObjectDomain($name){
		if(isset($this->_domainObjectIndex[$name])){
			if(array_key_exists('entity',$this->_domainObjectIndex[$name]) && is_object($_obj=$this->_domainObjectIndex[$name]['entity'])) {
				return $_obj;
			} else {
				$_rep=$this->_injector->get($this->_domainObjectIndex[$name]['class']);
				//$_rep=new $this->_domainObjectIndex[$name]['class']();
				$this->_domainObjectIndex[$name]['entity']=$_rep;
				$_rep->setMetadata($name,$this->_domainObjectIndex[$name]);
				return $_rep;
			}
		}
		throw new UndefinedDomainException('Unable to locate domain: '.$name);
	}

	public function getEntityManager(){
		if(isset($this->_entityManager)) return $this->_entityManager;
	}

	public function getSchema($name=null){
		if($name) return $this->domain($name)->getSchema();
		$result=array();
		foreach($this->_domainObjectIndex as $name=>$meta) $result[]=$this->domain($name)->getSchema();
		return $result;
	}
}

class UndefinedDomainException extends Exception {

}