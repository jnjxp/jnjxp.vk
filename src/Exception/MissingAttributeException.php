<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Exception;

use RuntimeException;

class MissingAttributeException extends RuntimeException implements ExceptionInterface
{
    public static function missing($type, $attr, $object)
    {
        return new self(sprintf(
            '%s not available as %s attribute in %s',
            $type,
            $attr,
            get_class($object)
        ));
    }
}
