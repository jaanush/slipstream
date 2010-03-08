<?php
namespace Slipstream\Common\Log\Event;

class OutputEventArgs extends \Slipstream\Common\Event\Args{
	/**
	 * @var unknown_type
	 */
	private $_data;
	/**
	 * @var string
	 */
	private $_label;
	/**
	 * @var string
	 */
	private $_type;
	/**
	 * @var array
	 */
	private $_options;
	/**
	 * @param unknown_type $data
	 * @param string $label
	 * @return unknown_type
	 */
	public function __construct($data,$type=null,$label=null,$options=null){
		$this->_data=$data;
		$this->_label=$label;
		$this->_type=$type;
		$this->_options=$options;
	}

	public function getData(){
		return $this->_data;
	}

	public function getLabel(){
		return $this->_label;
	}

	public function getType(){
		return $this->_type;
	}

	public function getOptions(){
		return $this->_options;
	}
}