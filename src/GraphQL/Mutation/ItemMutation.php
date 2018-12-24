<?php

namespace App\GraphQL\Mutation;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;

class ItemMutation
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     *
     * @return Item
     */
    public function create(string $name, float $price) :Item
    {
        $item = new Item();
        $item
            ->setName($name)
            ->setPrice($price)
        ;
        $this->em->persist($item);
        $this->em->flush();

        return $item;
    }
}
