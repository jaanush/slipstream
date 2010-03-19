<?php
namespace Slipstream\JsonRest\Component\Upload;

class AbstractUpload extends \Slipstream\JsonRest\Component\AbstractComponent{
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
		$this->log()->log(get_class($this),'Classname');
	}

	protected function formatReturnData($data=array()){
		if($this->header('user-agent')=='Shockwave Flash'){
				array_walk($data,function(&$val,$key){$val=$key.'='.$val;});
			return implode(',',$data);
		} else {
			return '<textarea>'.json_encode($data).'</textarea>';
		}
	}
}