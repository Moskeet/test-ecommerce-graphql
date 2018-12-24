<?php

namespace App\GraphQL\Resolver;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ItemResolver implements ResolverInterface
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
     * @param ResolveInfo $info
     * @param $value
     * @param Argument $args
     *
     * @return mixed
     */
    public function __invoke(ResolveInfo $info, $value, Argument $args)
    {
        $method = $info->fieldName;

        return $this->$method($value, $args);
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
}
