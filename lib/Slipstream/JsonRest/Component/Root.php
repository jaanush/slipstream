<?php
namespace Slipstream\JsonRest\Component;

class Root extends AbstractComponent{
	protected $_dom;

	public function __construct(\Slipstream\Common\DomainObjectManager $dom,
		\Slipstream\Common\Log\Handler $log){
		$this->_dom=$dom;
		parent::__construct($log);
	}

	public function map($name){
		if($name=='Class') return '\Slipstream\JsonRest\Component\ClassMaster';
		if($name=='Schema') return '\Slipstream\JsonRest\Component\SchemaMaster';
		if($name=='Upload') return '\Slipstream\JsonRest\Component\UploadMaster';
		if($this->_dom->domainExists($name)) return '\Slipstream\JsonRest\Component\DomainMaster';
	}
}