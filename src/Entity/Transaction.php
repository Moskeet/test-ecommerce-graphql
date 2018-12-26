<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Transaction
{
    use IdTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     inversedBy="transactions"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @return User
     */
    public function getOwner() :User
    {
        return $this->owner;
    }

    /**
     * @param User $value
     *
     * @return $this
     */
    public function setOwner(User $value) :self
    {
        $this->owner = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function setTotalPrice(float $value) :self
    {
        $this->totalPrice = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() :string
    {
        return $this->description;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setDescription(string $value) :self
    {
        $this->description = $value;

        return $this;
    }
}
