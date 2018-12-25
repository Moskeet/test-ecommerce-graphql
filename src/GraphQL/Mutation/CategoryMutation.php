<?php

namespace App\GraphQL\Mutation;

use App\Entity\Category;
use App\GraphQL\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryMutation implements MutationInterface
{
    use UserExtractorTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $name
     *
     * @return Category
     */
    public function create(string $name) :Category
    {
        $user = $this->extractUser($this->tokenStorage);

        if ($user === null) {
            throw new \LogicException(sprintf('isNull'));
        }

        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw new NotAuthorizedException();
        }

        $category = new Category();
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }
}
