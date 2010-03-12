<?php
namespace Slipstream\JsonRest;

class Configuration extends \Slipstream\Common\Configuration{
    public function __construct()
    {
        parent::__construct();

        $this->_attributes = array_replace($this->_attributes, array(
            'jsonRestBaseUrl' => null,
        	'jsonRestuploadFileManager'=>array(
        		'Image'=>array(
        			'class'=>'\Slipstream\JsonRest\Component\Upload\Image',
        			'basepath'=>SS_ROOT.'/htdocs/images/',
        			'baseurl'=>'/images/')
        		),
        	'jsonRestExposedEntities'=>array(),
        	'jsonRestQueryParsers'=>array('JsonPath')
        	)
        );
    }
}