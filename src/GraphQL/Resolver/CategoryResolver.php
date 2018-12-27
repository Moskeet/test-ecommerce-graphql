<?php

namespace App\GraphQL\Resolver;

use App\Entity\Category;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

class CategoryResolver implements ResolverInterface
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
     * @param int $id
     *
     * @return Category|null
     */
    public function resolve(int $id) :?Category
    {
        return $this->em->find(Category::class, $id);
    }

    /**
     * @param int $id
     *
     * @return Category
     */
    public function getCategory(int $id) :Category
    {
        return $this->em->find(Category::class, $id);
    }

    /**
     * @return Category[]
     */
    public function getCategories() :array
    {
        return $this->em
            ->getRepository(Category::class)
            ->findAll()
        ;
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
        $repository = $this->em->getRepository(Item::class);
        $paginator = new Paginator(function ($offset, $limit) use ($category, $repository) {
            return $repository
                ->findBy(
                    [
                        'category' => $category->getId(),
                    ],
                    [
                        'id' => 'ASC',
                    ],
                    $limit ?? 10,
                    $offset ?? 0)
                ;
        });

        return $paginator->auto($args, count($category->getItems()));
    }
}
