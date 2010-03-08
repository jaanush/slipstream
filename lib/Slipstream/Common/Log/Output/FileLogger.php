<?php
namespace Slipstream\Common\Log\Output;

class FileLogger implements Output{
	private $_configuration;
	private $_path;
	private $_file;

	public function __construct(\Slipstream\Common\ConfigurationInterface $configuration){
		$this->_configuration=$configuration;
		if(!$this->_path=$this->_configuration->getFileLoggerPath()) throw new \ErrorException('Logger output path not set');
		//print($this->_configuration->getFileLoggerPath());
		if(!$this->_file=fopen($this->_path,'w')) throw new \ErrorException('Unable to open logger output file');
	}

	public function onSlipstreamLogOutput(\Slipstream\Common\Log\Event\OutputEventArgs $event){
		fwrite($this->_file,$event->getType().' '.$event->getLabel().': '.print_r($event->getData(),true)."\n");
		//print_r($event->getData(),$event->getLabel(),$event->getType(),$event->getOptions());
	}

	public function getSubscribedEvents(){
		return array('onSlipstreamLogOutput');
	}
}