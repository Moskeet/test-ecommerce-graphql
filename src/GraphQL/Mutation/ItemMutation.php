<?php

namespace App\GraphQL\Mutation;

use App\Entity\Category;
use App\Entity\Item;
use App\GraphQL\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ItemMutation
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
     * @param int $category
     * @param string $name
     * @param float $price
     *
     * @return Item
     */
    public function createAction(int $category, string $name, float $price) :?Item
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw new NotAuthorizedException();
        }

        $category = $this->em->getRepository(Category::class)->find($category);
        $item = new Item();
        $item
            ->setCategory($category)
            ->setName($name)
            ->setPrice($price)
        ;
        $this->em->persist($item);
        $this->em->flush();

        return $item;
    }
}
