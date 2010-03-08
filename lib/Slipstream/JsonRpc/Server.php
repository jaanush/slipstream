<?php
namespace Slipstream\JsonRpc;

class Server{
	/**
	 * @var unknown_type
	 */
	private $_injector;
	/**
	 * @var unknown_type
	 */
	private $_eventManager;
	/**
	 * @var unknown_type
	 */
	private $_logHandler;
	/**
	 * @var unknown_type
	 */
	private $_configuration;
	private $_context;
	private $_entityManager;
	private $_domainObjectManager;

	public function __construct(\Slipstream\Common\ConfigurationInterface $configuration){
		$this->_configuration=$configuration;
		$this->injector()->set($this->_configuration);
		$this->injector()->register('\Slipstream\Common\ConfigurationInterface',get_class($this->_configuration));
		$this->eventManager();
		$this->log();//->log($this->_configuration);
	}

	public function run(){
		$request=json_decode(str_replace('undefined','null',file_get_contents('php://input')),true);
		//$this->log()->log(file_get_contents('php://input'),'Raw');
		$class=$_GET['class'];
		$this->log()->log($request,'Request');
		//$this->log()->log($class,'Class');
		//$this->log()->log($this->_domainObjectManager->domainExists($class),'Exists');
		$domain=$this->_domainObjectManager->domain($class);
		//$this->log()->log($domain,'Domain');
		$result=call_user_func_array(array($domain,$request['method']),$request['params']);
		print(json_encode($result));
		$this->log()->log($result,'Result');
	}

	public function setInjector(\Slipstream\Common\Injector\Injector $component){
		$this->_injector=$component;
		return $this;
	}

	protected function injector(){
		if(!isset($this->_injector)){
			if(class_exists('bucket_Container')){
				$this->_injector=new \Slipstream\Common\Injector\BucketAdapter(new \bucket_Container());
				//$this->_injector->register('\Slipstream\Common\ConfigurationInterface','Slipstream\JsonRest\Configuration');
				$this->_injector->register('Slipstream\Common\ConfigurationInterface','Slipstream\JsonRest\Configuration');
				$this->_injector->set($this->_configuration,'Slipstream\JsonRest\Configuration');
			}
		}
		return $this->_injector;
	}

	public function setEventManager(\Slipstream\Common\Event\Manager $eventManager){
		$this->_eventManager=$eventManager;
		return $this;
	}

	public function eventManager(){
		if(!isset($this->_eventManager)){
			$this->_eventManager=$this->injector()->get('Slipstream\Common\Event\Manager');
			$this->log()->setEventManager($this->_eventManager);
			//$this->_eventManager=new ssc\Event\Manager();
		}
		return $this->_eventManager;
	}

	public function setDomainObjectManager(\Slipstream\Common\DomainObjectManager $domainObjectManager){
		$this->_domainObjectManager=$domainObjectManager;
		$this->_domainObjectManager->setEventManager($this->eventManager());
		$this->injector()->set($this->_domainObjectManager,'Slipstream\Common\DomainObjectManager');
		return $this;
	}

	public function domainObjectManager(){
		if(!isset($this->_domainObjectManager)){
			$this->_domainObjectManager=$this->injector()->get('Slipstream\Common\DomainObjectManager');
		}
		return $this->_eventManager;
	}

	public function log(){
		if(!isset($this->_logHandler)){
			$this->_logHandler=$this->injector()->get('Slipstream\Common\Log\Handler');
			$this->injector()->setLogHandler($this->_logHandler);
			//$this->_logHandler==new \Slipstream\Common\Log\Handler($this->_configuration,$this->eventManager());
		}
		return $this->_logHandler;
	}
}