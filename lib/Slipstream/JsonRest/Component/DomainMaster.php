<?php
namespace Slipstream\JsonRest\Component;

class DomainMaster extends AbstractComponent{
	protected $_configuration;
	protected $_entityManager;
	protected $_dom;
	protected $_domain;

	public function dispatch(){
		$this->_domain=$this->_dom->domain($this->name());
		return parent::dispatch();
	}

	public function __construct(
		\Slipstream\Common\ConfigurationInterface $conf,
		\Slipstream\Common\DomainObjectManager $domainObjectManager,
		\Slipstream\Common\Log\Handler $log){
		$this->_configuration=$conf;
		$this->_dom=$domainObjectManager;
		parent::__construct($log);
	}

	public function map($name){
		return '\Slipstream\JsonRest\Component\DomainDetail';
	}

	public function get(){
		$response=$this->getDomain()->getAll();
		//$this->log()->log(json_encode($response));
		return new \k_JsonResponse($response);
	}

	public function post(){
		$response= new \k_JsonResponse($data=$this->_domain->add(json_decode($this->context->rawHttpRequestBody(),1)));
		$response->setHeader('Location','/ssres/'.$this->name().'/'.$this->_domain->getIdFromData($data));
		$response->setStatus(201);
		return $response;
		//$this->log()->log($this->context->rawHttpRequestBody());
	}

	public function getDomain(){
		return  $this->_domain;
	}
}