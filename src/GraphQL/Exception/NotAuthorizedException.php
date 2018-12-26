<?php

namespace App\GraphQL\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class NotAuthorizedException extends \LogicException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Not authorized action.', Response::HTTP_FORBIDDEN, $previous);
    }
}
