<?php

namespace App\GraphQL\Resolver;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserResolver implements ResolverInterface
{
    use InvokeTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EntityManagerInterface $em
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return string
     */
    public function getAuthToken(string $username, string $password) :string
    {
        /** @var User|null $user */
        $user = $this->em->getRepository(User::class)->findOneBy([
            'username' => $username,
        ]);

        if (!$user) {
            return '';
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if(!$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
            return '';
        }

        return md5($user->getPassword());
    }
}
