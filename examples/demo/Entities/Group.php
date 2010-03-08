<?php

namespace demo\Entities;

/**
 * @Entity
 * @Table(name="`group`")
 */
class Group
{
    /** @Id @Column(type="integer") */
    private $id;
    /** @Column(type="string") */
    private $name;
  /**
   * @ManyToMany(targetEntity="User")
   * @JoinTable(name="user_groups",
   *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
   *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
   *      )
   */
  private $users;
    /**
     * @OneToMany(targetEntity="Group", mappedBy="parent")
     */
    private $children;

    /**
     * @ManyToOne(targetEntity="Group")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
}