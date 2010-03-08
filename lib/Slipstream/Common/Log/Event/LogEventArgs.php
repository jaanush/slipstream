<?php
namespace Slipstream\Common\Log\Event;

class LogEventArgs extends \Slipstream\Common\Event\Args{
	/**
	 * @var unknown_type
	 */
	private $_data;
	/**
	 * @var string
	 */
	private $_label;
	/**
	 * @param unknown_type $data
	 * @param string $label
	 * @return unknown_type
	 */
	public function __construct($data,$label=null){
		$this->_data=$data;
		$this->_label=$label;
	}

	public function getData(){
		return $this->_data;
	}

	public function getLabel(){
		return $this->_label;
	}
}