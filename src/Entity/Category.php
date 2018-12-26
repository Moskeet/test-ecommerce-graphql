<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Category
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250)
     */
    private $name;

    /**
     * @var Item[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Category",
     *     mappedBy="category"
     * )
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return Item[]|Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Item[] $value
     * @return $this
     */
    public function setItems(array $value): self
    {
        $this->items = $value;

        return $this;
    }

    /**
     * @param Item $value
     * @return $this
     */
    public function addItem($value): self
    {
        $this->items[] = $value;

        return $this;
    }

    /**
     * @param Item $value
     * @return $this
     */
    public function removeItem(Item $value): self
    {
        if ($this->items->contains($value)) {
            $this->items->removeElement($value);
        }

        return $this;
    }
}
