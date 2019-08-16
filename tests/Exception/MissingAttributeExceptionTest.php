<?php

namespace Test\Jnjxp\Vk\Exception;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Exception\MissingAttributeException;

/**
 * Class MissingAttributeExceptionTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Exception\MissingAttributeException
 */
class MissingAttributeExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = MissingAttributeException::missing(
            'foo',
            'bar',
            $this
        );

        $expect = sprintf(
            '%s not available as %s attribute in %s',
            'foo',
            'bar',
            get_class($this)
        );

        $this->assertEquals($expect, $exception->getMessage());
    }
}
