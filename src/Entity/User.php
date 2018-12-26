<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\Repository\UserRepository")
 * @ORM\Table
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
     * @var Transaction[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Transaction",
     *     mappedBy="owner"
     * )
     */
    private $transactions;

    public function __construct()
    {
        parent::__construct();
        $this->transactions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getToken() :string
    {
        return md5($this->getPassword());
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Transaction[] $value
     * @return $this
     */
    public function setTransactions(array $value) :self
    {
        $this->transactions = $value;

        return $this;
    }

    /**
     * @param Transaction $value
     *
     * @return $this
     */
    public function addTransaction($value) :self
    {
        $this->transactions[] = $value;

        return $this;
    }
}
