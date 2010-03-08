<?php

namespace demo\Entities;

/**
 * @Entity
 * @Table(name="`user`")
 */
class User extends \Slipstream\Doctrine\AbstractDomainObject
{
    /** @Id @Column(type="integer") */
    private $id;
    /** @Column(type="string") */
    private $username;
    /** @Column(type="string") */
    private $firstname;
    /** @Column(type="string") */
    private $lastname;
    /** @Column(type="string") */
    private $password;
    /**
     * @OneToMany(targetEntity="\demo\Entities\Email", mappedBy="user")
     */
    private $emails;
    /**
     * @ManyToMany(targetEntity="\demo\Entities\Group", mappedBy="users")
     */
    private $groups;

    public function __construct() {
        $this->emails = new \Doctrine\Common\Collections\ArrayCollection;
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function getFullData(){
    	return array(
    		'id'=>$this->id,
    		'username'=>$this->username,
    		'firstname'=>$this->firstname,
    		'lastname'=>$this->lastname,
    		'password'=>$this->password,
    		'emails'=>$this->emails->getFullData(),
    		'groups'=>$this->groups->getFullData(),
    	);
    }

    public function getFullObject(){
    	return array('id'=>$this->id,'firstname'=>$this->lastname);
    }

    public function getFullName(){
    	return $this->firstname.' '.$this->lastname;
    }
    public function getEmails(){
    	return $this->emails;
    }

    public function getFullData(){
    	$_data=get_object_vars($this);
    	foreach($_data as $_key=>$_val){
    		if(is_object($_val)){
    			$_data[$_key]=$_val->getKeys();
    		}
    	}
    }
/*
    public function getEmails(){
    	$result=array();
    	foreach($this->emails as $val){
    		$result[]=$val->getEmail();
    	}
    	return $result;
    }
*/
	public function getSchema(){
		$_schema=parent::getSchema();
		$_schema['id']='User';
		$_schema['instances']=array('$ref'=>'../User/');
		return $_schema;
	}
}