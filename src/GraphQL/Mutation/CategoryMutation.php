<?php

namespace App\GraphQL\Mutation;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class CategoryMutation implements MutationInterface
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
     * @return Category
     */
    public function create(string $name) :Category
    {
        $category = new Category();
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }
}
