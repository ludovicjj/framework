<?php

namespace Tests\Domain\Admin\Handler\Posts;

use App\Domain\Admin\Handler\Posts\CreateHandler;
use App\Domain\Common\Form\Interfaces\FormInterface;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use App\Domain\Entity\PostEntity;
use App\Domain\Repository\PostRepository;
use Tests\DatabaseTestCase;

class CreateHandlerTest extends DatabaseTestCase
{
    /** @var CreateHandler */
    private $handler;

    /** @var PostRepository */
    private $postRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new PostRepository($this->pdo);
        $this->handler = new CreateHandler(
            $this->postRepository,
            $this->prophesize(FlashBagInterface::class)->reveal()
        );
    }

    public function testHandleWithMethodGetAndValidForm()
    {

        $form = $this->createMock(FormInterface::class);
        $form->method('isValid')->willReturn(true);
        $form->method('isSubmitted')->willReturn(false);

        $result = $this->handler->handle($form);
        $this->assertFalse($result);
    }

    public function testHandleWithMethodPostAndValidForm()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isValid')->willReturn(true);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('getData')->willReturn(
            [
                'name'=> 'demo',
                'slug' => 'demo-slug',
                'content' => 'my content'
            ]
        );
        $result = $this->handler->handle($form);
        $this->assertTrue($result);
    }

    public function testInsertInCreateHandler()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isValid')->willReturn(true);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('getData')->willReturn(
            [
                'name'=> 'demo',
                'slug' => 'demo-test',
                'content' => 'my awesome content'
            ]
        );
        $this->handler->handle($form);

        $post = $this->postRepository->find(1);
        $this->assertInstanceOf(PostEntity::class, $post);
        $this->assertEquals('demo', $post->getName());
        $this->assertEquals('demo-test', $post->getSlug());
        $this->assertEquals('my awesome content', $post->getContent());
    }

    /**
     * @throws \ReflectionException
     */
    public function testFilterParams()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn(
            [
                'name'=> 'demo',
                'slug' => 'demo-test',
                'content' => 'my awesome content',
                'leviathan' => 'alt 236'
            ]
        );
        $filterParams = self::callPrivateMethod($this->handler, 'getFilterParams', [$form->getData()]);
        $this->assertIsArray($filterParams);
        $this->assertCount(3, $filterParams);
        $this->assertSame(
            ['name' => 'demo', 'slug' => 'demo-test', 'content' => 'my awesome content'],
            $filterParams
        );
    }

    /**
     * Static method to test private or protected method
     * By using reflexion to set them to be public.
     *
     * @param $obj
     * @param string $name
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public static function callPrivateMethod($obj, string $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}
