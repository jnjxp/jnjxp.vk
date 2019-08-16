<?php

namespace Jnjxp\Vk\Aware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Aware\FlashAwareTrait;
use Zend\Diactoros\ServerRequest;
use Jnjxp\Vk\Exception\MissingAttributeException;
use Zend\Expressive\Flash\FlashMessagesInterface;

/**
 * Class FlashAwareTraitTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Aware\FlashAwareTrait
 */
class FlashAwareTraitTest extends TestCase
{
    /**
     * @var FlashAwareTrait $flashAwareTrait An instance of "FlashAwareTrait" to test.
     */
    private $flashAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->flashAwareTrait = $this->getMockBuilder(FlashAwareTrait::class)
            ->disableOriginalConstructor()
            ->getMockForTrait();
    }

    /**
     * @covers \Jnjxp\Vk\Aware\FlashAwareTrait::setFlashAttribute
     */
    public function testSetFlashAttribute(): void
    {
        $expected = "a string to test";

        $property = (new \ReflectionClass($this->flashAwareTrait))
            ->getProperty('flashAttribute');
        $property->setAccessible(true);
        $this->flashAwareTrait->setFlashAttribute($expected);

        $this->assertSame($expected, $property->getValue($this->flashAwareTrait));
    }

    public function testFlashes()
    {
        $fake = new class {
            use FlashAwareTrait;

            public function test($request)
            {
                return $this->flashMessage($request, 'foo', 'bar');
            }
        };

        $request = new ServerRequest();
        $flash = $this->createMock(FlashMessagesInterface::class);
        $flash->expects($this->once())
              ->method('flash')
              ->with(
                  $this->equalTo('foo'),
                  $this->equalTo('bar'),
              );

        $request = $request->withAttribute('flash', $flash);


        $fake->test($request);
    }
}
