<?php
namespace Slipstream\Common\Data\Driver;

class DoctrineDriver extends AbstractDriver implements ArrayAccess{
	protected $_domainObject;

	public function fetchById($id){

	}

    public function offsetExists($offset) {
        // In this example we say that exists means it is not null
        $value = $this->{"get$offset"}();
        return $value !== null;
    }

    public function offsetSet($offset, $value) {
        $this->{"set$offset"}($value);
    }

    public function offsetGet($offset) {
        return $this->{"get$offset"}();
    }

    public function offsetUnset($offset) {
        $this->{"set$offset"}(null);
    }
}
