<?php

namespace App\GraphQL\Resolver;

use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;

trait InvokeTrait
{
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
}
