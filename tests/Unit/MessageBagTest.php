<?php

defined('ABSPATH') || exit;

declare(strict_types=1);

namespace WPZylos\Framework\Validation\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Validation\MessageBag;

/**
 * Tests for MessageBag.
 */
class MessageBagTest extends TestCase
{
    public function testIsEmptyByDefault(): void
    {
        $bag = new MessageBag();
        $this->assertSame(0, $bag->count());
        $this->assertEmpty($bag->all());
    }

    public function testAddMessage(): void
    {
        $bag = new MessageBag();
        $bag->add('email', 'Invalid email');

        $this->assertTrue($bag->has('email'));
        $this->assertSame(['Invalid email'], $bag->get('email'));
    }

    public function testFirstReturnsFirstError(): void
    {
        $bag = new MessageBag();
        $bag->add('name', 'First error');
        $bag->add('name', 'Second error');

        $this->assertSame('First error', $bag->first('name'));
    }

    public function testFirstReturnsNullForMissing(): void
    {
        $bag = new MessageBag();
        $this->assertNull($bag->first('missing'));
    }

    public function testGetReturnsEmptyArrayForMissing(): void
    {
        $bag = new MessageBag();
        $this->assertSame([], $bag->get('nonexistent'));
    }

    public function testAllReturnsAllMessages(): void
    {
        $bag = new MessageBag();
        $bag->add('a', 'Error A');
        $bag->add('b', 'Error B');

        $all = $bag->all();
        $this->assertArrayHasKey('a', $all);
        $this->assertArrayHasKey('b', $all);
    }

    public function testFlattenReturnsAllMessagesAsFlatArray(): void
    {
        $bag = new MessageBag();
        $bag->add('a', 'Error 1');
        $bag->add('a', 'Error 2');
        $bag->add('b', 'Error 3');

        $flat = $bag->flatten();
        $this->assertCount(3, $flat);
        $this->assertContains('Error 1', $flat);
        $this->assertContains('Error 3', $flat);
    }

    public function testCountReturnsTotalErrors(): void
    {
        $bag = new MessageBag();
        $bag->add('a', 'One');
        $bag->add('b', 'Two');
        $bag->add('b', 'Three');

        $this->assertSame(3, $bag->count());
    }

    public function testKeysReturnsFieldNames(): void
    {
        $bag = new MessageBag();
        $bag->add('email', 'Error');
        $bag->add('name', 'Error');

        $this->assertEqualsCanonicalizing(['email', 'name'], $bag->keys());
    }

    public function testToArrayMatchesAll(): void
    {
        $bag = new MessageBag();
        $bag->add('field', 'Error');

        $this->assertSame($bag->all(), $bag->toArray());
    }
}
