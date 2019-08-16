<?php

namespace Test\Jnjxp\Vk\Aware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Aware\SessionAwareTrait;
use Zend\Diactoros\ServerRequest;
use Jnjxp\Vk\Exception\MissingAttributeException;

/**
 * Class SessionAwareTraitTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Aware\SessionAwareTrait
 */
class SessionAwareTraitTest extends TestCase
{
    /**
     * @var SessionAwareTrait $sessionAwareTrait An instance of "SessionAwareTrait" to test.
     */
    private $sessionAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->sessionAwareTrait = $this->getMockBuilder(SessionAwareTrait::class)
            ->disableOriginalConstructor()
            ->getMockForTrait();
    }

    /**
     * @covers \Jnjxp\Vk\Aware\SessionAwareTrait::setSessionAttribute
     */
    public function testSetSessionAttribute(): void
    {
        $expected = "a string to test";

        $property = (new \ReflectionClass($this->sessionAwareTrait))
            ->getProperty('sessionAttribute');
        $property->setAccessible(true);
        $this->sessionAwareTrait->setSessionAttribute($expected);

        $this->assertSame($expected, $property->getValue($this->sessionAwareTrait));
    }

    public function testUnavailable()
    {
        $fake = new class {
            use SessionAwareTrait;

            public function test($request)
            {
                return $this->getSession($request);
            }
        };

        $this->expectException(MissingAttributeException::class);
        $fake->test(new ServerRequest());
    }
}
