<?php

namespace App\GraphQL\Resolver;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

class CategoryResolver implements ResolverInterface
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
     * @return Category
     */
    public function resolve(string $id) :Category
    {
        return $this->em->find(Category::class, $id);
    }

    /**
     * @param Category $category
     *
     * @return int
     */
    public function id(Category $category) :int
    {
        return $category->getId();
    }

    /**
     * @param Category $category
     *
     * @return string
     */
    public function name(Category $category) :string
    {
        return $category->getName();
    }

    /**
     * @param Category $category
     * @param Argument $args
     *
     * @return Connection
     */
    public function items(Category $category, Argument $args) :Connection
    {
        $items = $category->getItems();
        $paginator = new Paginator(function ($offset, $limit) use ($items) {
            return array_slice($items, $offset, $limit ?? 10);
        });

        return $paginator->auto($args, count($items));
    }
}
