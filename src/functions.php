<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
use ZeroConfig\Cloner\Cloner;

// @codeCoverageIgnoreStart
if (!function_exists('deepClone')) {
// @codeCoverageIgnoreEnd
    /**
     * Perform a deep clone on the subject.
     *
     * @param object $subject
     *
     * @return object
     *
     * @codeCoverageIgnore
     */
    function deepClone(object $subject): object
    {
        static $cloner;

        if (!$cloner) {
            $cloner = new Cloner();
        }

        return $cloner($subject);
    }
// @codeCoverageIgnoreStart
}

// @codeCoverageIgnoreEnd

// @codeCoverageIgnoreStart
if (!function_exists('🗐')) {
    // @codeCoverageIgnoreEnd
    /** @noinspection NonAsciiCharacters */
    /**
     * @param object $object
     *
     * @return object
     *
     * @codeCoverageIgnore
     * @deprecated In favor of 🐑
     */
    function 🗐(object $object): object // @codingStandardsIgnoreLine
    {
        return deepClone($object);
    }
    // @codeCoverageIgnoreStart
}

// @codeCoverageIgnoreEnd

// @codeCoverageIgnoreStart
if (!function_exists('🐑')) {
    // @codeCoverageIgnoreEnd
    /** @noinspection NonAsciiCharacters */
    /**
     * @param object $object
     *
     * @return object
     *
     * @codeCoverageIgnore
     */
    function 🐑(object $object): object // @codingStandardsIgnoreLine
    {
        return deepClone($object);
    }
    // @codeCoverageIgnoreStart
}

// @codeCoverageIgnoreEnd
