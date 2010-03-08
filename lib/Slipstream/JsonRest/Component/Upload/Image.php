<?php
namespace Slipstream\JsonRest\Component\Upload;

class Image extends \Slipstream\JsonRest\Component\Upload\AbstractUpload{
	protected $_location;

	public function __construct(
		\Slipstream\Common\ConfigurationInterface $conf,
		\Slipstream\Common\DomainObjectManager $domainObjectManager,
		\Slipstream\Common\Event\Manager $eventManager,
		\Slipstream\Common\Log\Handler $log){
		parent::__construct($conf,$domainObjectManager,$eventManager,$log);
		$jrfum=$this->_configuration->getJsonRestuploadFileManager();
		$this->_location=$jrfum['Image']['basepath'];
		$this->_urlbase=$jrfum['Image']['baseurl'];
	}
	public function postMultipart(){
		/*$this->log()->group('Upload Image');
		$this->log()->log($this->header(),'Header');
		$this->log()->log($this->query(),'Query');
		$this->log()->log($this->body(),'Body');
		$this->log()->log($this->file(),'File');
		$this->log()->groupEnd();*/
		$result=$this->query();
		foreach($this->file() as $file){
			$this->log()->log($file->writeTo($loc=($this->_location.$file->name())),'Save');
			$result['file']=$loc;
			$result['path']=$this->_urlbase.$file->name();
			$result['name']=$file->name();
		}
		$result=$this->formatReturnData($result);
		$this->log()->log($result,'Result');
		return $result;
	}
}