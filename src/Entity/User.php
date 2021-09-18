<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $balance = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionHistoryLogs", mappedBy="user")
     */
    private $logs;

    public function __construct()
    {
        parent::__construct();

        $this->logs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param $balance
     * @return $this
     */
    public function setBalance($balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param mixed $logs
     */
    public function setLogs($logs): void
    {
        $this->logs = $logs;
    }

}