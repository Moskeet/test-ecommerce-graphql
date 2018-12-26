<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\Repository\BasketRepository")
 * @ORM\Table
 */
class Basket
{
    use IdTrait;

    /**
     * @var User
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\User"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    /**
     * @var BasketItem[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BasketItem",
     *     mappedBy="basket",
     *     cascade={"persist"}
     * )
     */
    private $basketItems;

    public function __construct()
    {
        $this->basketItems = new ArrayCollection();
    }

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
     * @return BasketItem[]|ArrayCollection
     */
    public function getBasketItems() :ArrayCollection
    {
        return $this->basketItems;
    }

    /**
     * @param BasketItem[] $value
     *
     * @return $this
     */
    public function setBasketItems(array $value) :self
    {
        $this->basketItems = $value;

        return $this;
    }

    /**
     * @param BasketItem $value
     *
     * @return $this
     */
    public function addBasketItem($value) :self
    {
        $this->basketItems[] = $value;

        return $this;
    }

    /**
     * @param BasketItem $value
     *
     * @return $this
     */
    public function removeBasketItem($value) :self
    {
        if (($key = array_search($value, $this->basketItems, true)) !== false) {
            array_splice($this->basketItems, $key, 1);
        }

        return $this;
    }
}
