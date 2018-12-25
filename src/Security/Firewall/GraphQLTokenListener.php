<?php

namespace App\Security\Firewall;

use App\Entity\User;
use App\Security\Authentication\Token\GraphQLToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class GraphQLTokenListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        EntityManagerInterface  $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->em = $em;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->headers->has('X-AUTH-TOKEN')) {
            return;
        }

        $user = $this->em
            ->getRepository(User::class)
            ->getUserByToken($request->headers->get('X-AUTH-TOKEN'))
        ;

        if ($user === null) {
            $this->tokenStorage->setToken(null);
            $response = new Response('', Response::HTTP_FORBIDDEN);
            $event->setResponse($response);

            return;
        }

        $token = new GraphQLToken();
        $token->setUser($user);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            $this->tokenStorage->setToken(null);
        }

        // By default deny authorization
        $response = new Response('', Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
