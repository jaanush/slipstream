<?php
namespace Slipstream\JsonRest\Component;

class UploadMaster extends AbstractComponent{
	protected $_configuration;
	protected $_dom;
	protected $_eventManager;

	public function __construct(
		\Slipstream\Common\ConfigurationInterface $conf,
		\Slipstream\Common\DomainObjectManager $domainObjectManager,
		\Slipstream\Common\Event\Manager $eventManager,
		\Slipstream\Common\Log\Handler $log){
		$this->_configuration=$conf;
		$this->_dom=$domainObjectManager;
		parent::__construct($log);
	}

	public function map($name){
		$lookup=$this->_configuration->getJsonRestuploadFileManager();
		$this->log()->log($lookup,'Mapping Upload '.$name);
		if(array_key_exists($name,$lookup)) return $lookup[$name]['class'];
	}
}