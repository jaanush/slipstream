<?php
namespace data\DataObj;

class Entry extends \Slipstream\Common\Data\Driver\DataObjDriver{
	public function __construct(){
		$this->load(__DIR__.'/EntryData.ser');
	}
	/*protected $data=array(
		1=>array('id'=>1,'firstname'=>'Jaanus','lastname'=>'Heeringson'),
		2=>array('id'=>2,'firstname'=>'Madelaine','lastname'=>'Pettersson Holsten')
	);*/
}
