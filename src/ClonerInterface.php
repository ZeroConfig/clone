<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace ZeroConfig\Cloner;

interface ClonerInterface
{
    /**
     * Clone the given object.
     *
     * @param object $subject
     *
     * @return object
     */
    public function __invoke(object $subject): object;
}
