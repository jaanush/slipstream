<?php
namespace Slipstream\JsonRest\Component;

class SchemaMaster extends AbstractComponent{
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

	public function map($name){
		if($this->_dom->domainExists($name)) return '\Slipstream\JsonRest\Component\SchemaDetail';
	}

	public function get(){
		return new \k_JsonResponse($this->_dom->getSchema());
	}
}