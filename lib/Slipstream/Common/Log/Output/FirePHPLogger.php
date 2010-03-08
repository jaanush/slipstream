<?php
namespace Slipstream\Common\Log\Output;

class FirePHPLogger implements Output{
	private $firephp;

	public function __construct(){
		if(!class_exists('\FirePHP')) throw new \ErrorException('FirePHP not found');
		$this->firephp=\FirePHP::getInstance(true);
	}

	public function onSlipstreamLogOutput(\Slipstream\Common\Log\Event\OutputEventArgs $event){
		$this->firephp->fb($event->getData(),$event->getLabel(),$event->getType(),$event->getOptions());
	}

	public function getSubscribedEvents(){
		return array('onSlipstreamLogOutput');
	}
}