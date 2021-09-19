<?php

namespace App\Model;

use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as Serializer;

/**
 * @SWG\Definition()
 */
class UserShortOut
{
    /**
     * @Serializer\Type("string")
     */
    public $username;
}