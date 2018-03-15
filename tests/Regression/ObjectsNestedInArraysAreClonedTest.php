<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace ZeroConfig\Cloner\Tests\Regression;

use PHPUnit\Framework\TestCase;
use ZeroConfig\Cloner\Cloner;
use ZeroConfig\Cloner\Tests\Fixture\Entry;
use ZeroConfig\Cloner\Tests\Fixture\Registry;

/**
 * @coversNothing
 */
class ObjectsNestedInArraysAreClonedTest extends TestCase
{
    /**
     * @return void
     */
    public function testObjectsNestedInArraysAreCloned(): void
    {
        $subject = new Registry();
        $subject->register('foo', new Entry('original'));

        $cloner = new Cloner();

        /** @var Registry $clone */
        $clone = $cloner($subject);

        $subject->get('foo')->setMessage('malformed');

        $this->assertEquals(
            'original',
            $clone->get('foo')->getMessage(),
            'The cloned registry should have no reference to the original.'
        );

        $subject->register('foo', new Entry('updated'));

        $this->assertEquals(
            'original',
            $clone->get('foo')->getMessage(),
            'The cloned registry should have no reference to the original.'
        );

        $clone->register('foo', new Entry('cloned'));

        $this->assertEquals(
            'updated',
            $subject->get('foo')->getMessage(),
            'The original registry should not be affected by its clone.'
        );
    }
}
