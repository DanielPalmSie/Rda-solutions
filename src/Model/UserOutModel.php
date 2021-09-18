<?php

namespace App\Model;

use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as Serializer;
/**
 * @SWG\Definition()
 */
class UserOutModel
{
    /**
     * @Serializer\Type("string")
     */
    public $username;

    /**
     * @Serializer\Type("string")
     */
    public $email;

    /**
     * @Serializer\Type("integer")
     */
    public $balance;

}