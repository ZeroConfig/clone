<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace ZeroConfig\Cloner;

use ReflectionObject;
use SplObjectStorage;

class Cloner implements ClonerInterface
{
    /**
     * Clone the given object.
     *
     * @param object $subject
     *
     * @return object
     */
    public function __invoke(object $subject): object
    {
        return $this->cloneObject($subject, new SplObjectStorage());
    }

    /**
     * Clone the given object using the given context.
     *
     * @param object           $subject
     * @param SplObjectStorage $context
     *
     * @return object
     */
    private function cloneObject(
        object $subject,
        SplObjectStorage $context
    ): object {
        if ($context->offsetExists($subject)) {
            return $context->offsetGet($subject);
        }

        $copy              = clone $subject;
        $context[$subject] = $copy;
        $reflection        = new ReflectionObject($copy);

        foreach ($reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $property->setAccessible(true);
            $value = $property->getValue($copy);

            if (is_object($value)) {
                $property->setValue(
                    $copy,
                    $this->cloneObject($value, $context)
                );
            }

            $property->setAccessible(false);
        }

        return $copy;
    }
}
