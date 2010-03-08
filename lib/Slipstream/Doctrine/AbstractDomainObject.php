<?php
namespace Slipstream\Doctrine;

abstract class AbstractDomainObject{
	public function getSchema(){
		return array(
			'extends'=>array(
				'$ref'=>'Object'
			),
			'prototype'=>(object)array());
	}
}