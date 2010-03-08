<?php
namespace Slipstream\JsonRest\Component;

class DomainDetail extends AbstractComponent{
	protected $_configuration;
	protected $_entityManager;
	protected $_dom;

	public function __construct(
		\Slipstream\Common\ConfigurationInterface $conf,
		\Slipstream\Common\DomainObjectManager $domainObjectManager,
		\Slipstream\Common\Log\Handler $log){
		$this->_configuration=$conf;
		$this->_dom=$domainObjectManager;
		parent::__construct($log);
	}

	public function get(){
		$response=$this->context->getDomain()->find($this->name());
		return new \k_JsonResponse($response);
	}

	public function put(){
		return new \k_JsonResponse($this->context->getDomain()->set(json_decode($this->context->rawHttpRequestBody(),1)));
		//$this->log()->log($this->context->rawHttpRequestBody());
	}
}