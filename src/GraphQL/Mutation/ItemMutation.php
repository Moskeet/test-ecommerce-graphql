<?php

namespace App\GraphQL\Mutation;

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
     * @param string $name
     *
     * @return Item
     */
    public function create(string $name, float $price) :?Item
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw new NotAuthorizedException();
        }

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
