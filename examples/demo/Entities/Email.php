<?php

namespace demo\Entities;

/**
 * @Entity
 * @Table(name="`email`")
 */
class Email
{
    /** @Id @Column(type="integer") */
    private $id;
    /** @Column(type="string") */
    private $email;
    /**
     * @ManyToOne(targetEntity="\demo\Entities\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function getEmail(){
    	return $this->email;
    }
}