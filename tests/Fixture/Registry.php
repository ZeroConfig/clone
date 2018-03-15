<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace ZeroConfig\Cloner\Tests\Fixture;

final class Registry
{
    /** @var Entry[] */
    private $entries = [];

    /**
     * @param string $identifier
     * @param Entry  $entry
     *
     * @return void
     */
    public function register(string $identifier, Entry $entry): void
    {
        $this->entries[$identifier] = $entry;
    }

    /**
     * @param string $identifier
     *
     * @return null|Entry
     */
    public function get(string $identifier): ?Entry
    {
        return $this->entries[$identifier] ?? null;
    }
}
