<?php

namespace Test\Jnjxp\Vk\Aware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Aware\AuthAwareTrait;
use Zend\Diactoros\ServerRequest;
use Jnjxp\Vk\Exception\MissingAttributeException;

/**
 * Class AuthAwareTraitTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Aware\AuthAwareTrait
 */
class AuthAwareTraitTest extends TestCase
{
    /**
     * @var AuthAwareTrait $authAwareTrait An instance of "AuthAwareTrait" to test.
     */
    private $authAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->authAwareTrait = $this->getMockBuilder(AuthAwareTrait::class)
            ->disableOriginalConstructor()
            ->getMockForTrait();
    }

    /**
     * @covers \Jnjxp\Vk\Aware\AuthAwareTrait::setAuthAttribute
     */
    public function testSetAuthAttribute(): void
    {
        $expected = "a string to test";

        $property = (new \ReflectionClass($this->authAwareTrait))
            ->getProperty('authAttribute');
        $property->setAccessible(true);
        $this->authAwareTrait->setAuthAttribute($expected);

        $this->assertSame($expected, $property->getValue($this->authAwareTrait));
    }


    public function testUnavailable()
    {
        $fake = new class {
            use AuthAwareTrait;

            public function test($request)
            {
                return $this->getAuthHelper($request);
            }
        };

        $this->expectException(MissingAttributeException::class);
        $fake->test(new ServerRequest());
    }
}
