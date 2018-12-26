<?php

namespace App\GraphQL\Resolver;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ItemResolver implements ResolverInterface
{
    use InvokeTrait;

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
     * @param string $id
     *
     * @return Item
     */
    public function resolve(string $id) :Item
    {
        return $this->em->find(Item::class, $id);
    }

    /**
     * @param string $id
     *
     * @return Item
     */
    public function getItem(string $id) :Item
    {
        return $this->em->find(Item::class, $id);
    }

    /**
     * @param int $categoryId
     *
     * @return array
     */
    public function getCollection(int $categoryId) :array
    {
        return $this->em
            ->getRepository(Item::class)
            ->findBy([
                'category' => $categoryId,
            ]);
    }

    /**
     * @param Item $Item
     *
     * @return int
     */
    public function id(Item $Item) :int
    {
        return $Item->getId();
    }

    /**
     * @param Item $Item
     *
     * @return string
     */
    public function name(Item $Item) :string
    {
        return $Item->getName();
    }

    /**
     * @param Item $Item
     *
     * @return string
     */
    public function price(Item $Item) :string
    {
        return $Item->getPrice();
    }
}
