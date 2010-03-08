<?php
namespace Slipstream\JsonRest\Component;

class SchemaDetail extends AbstractComponent{
	protected $_configuration;
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
		return new \k_JsonResponse($this->_dom->getSchema($this->name()));
	}
}