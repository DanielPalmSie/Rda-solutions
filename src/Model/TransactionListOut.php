<?php

namespace App\Model;

use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as Serializer;

/**
 * @SWG\Definition()
 */
class TransactionListOut
{
    /**
     * @Serializer\Type("integer")
     */
    public $id;

    /**
     * @Serializer\Type("array")
     */
    public $user;

    /**
     * @Serializer\Type("integer")
     */
    public $send;

    /**
     * @Serializer\Type("integer")
     */
    public $receive;

    /**
     * @Serializer\Type("DateTime")
     */
    public $createdAt;
}