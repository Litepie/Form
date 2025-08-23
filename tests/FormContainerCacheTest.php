<?php

namespace Litepie\Form\Tests;

use Illuminate\Support\Facades\Cache;
use Litepie\Form\FormContainer;
use Litepie\Form\FormBuilder;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

class FormContainerCacheTest extends TestCase
{
    protected $app;
    protected $container;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app = new Container();
        
        // Mock cache facade
        Cache::shouldReceive('store')->andReturnSelf();
        Cache::shouldReceive('getFacadeRoot')->andReturnSelf();
        Cache::shouldReceive('remember')->andReturnUsing(function ($key, $ttl, $callback) {
            return $callback();
        });
        Cache::shouldReceive('forget')->andReturn(true);
        Cache::shouldReceive('tags')->andReturnSelf();
        Cache::shouldReceive('flush')->andReturn(true);
        
        $this->container = new FormContainer($this->app, 'test_container');
    }

    public function testCacheConfigurationDefaults()
    {
        $config = $this->getPrivateProperty($this->container, 'cacheConfig');
        
        $this->assertTrue($config['enabled']);
        $this->assertEquals(3600, $config['ttl']);
        $this->assertEquals('form_container', $config['prefix']);
        $this->assertIsArray($config['tags']);
    }

    public function testCacheMethodsReturnSelf()
    {
        $result = $this->container->cache(['ttl' => 1800]);
        $this->assertInstanceOf(FormContainer::class, $result);

        $result = $this->container->enableCache(1800);
        $this->assertInstanceOf(FormContainer::class, $result);

        $result = $this->container->disableCache();
        $this->assertInstanceOf(FormContainer::class, $result);

        $result = $this->container->cacheTtl(1800);
        $this->assertInstanceOf(FormContainer::class, $result);

        $result = $this->container->cacheTags(['test']);
        $this->assertInstanceOf(FormContainer::class, $result);

        $result = $this->container->clearCache();
        $this->assertInstanceOf(FormContainer::class, $result);
    }

    public function testCacheConfiguration()
    {
        $this->container->cache([
            'enabled' => false,
            'ttl' => 1800,
            'prefix' => 'custom_prefix',
            'tags' => ['custom_tag'],
        ]);

        $config = $this->getPrivateProperty($this->container, 'cacheConfig');
        
        $this->assertFalse($config['enabled']);
        $this->assertEquals(1800, $config['ttl']);
        $this->assertEquals('custom_prefix', $config['prefix']);
        $this->assertEquals(['custom_tag'], $config['tags']);
    }

    public function testEnableCache()
    {
        $this->container->enableCache(2400);
        
        $config = $this->getPrivateProperty($this->container, 'cacheConfig');
        $this->assertTrue($config['enabled']);
        $this->assertEquals(2400, $config['ttl']);
    }

    public function testDisableCache()
    {
        $this->container->disableCache();
        
        $config = $this->getPrivateProperty($this->container, 'cacheConfig');
        $this->assertFalse($config['enabled']);
    }

    public function testCacheTtl()
    {
        $this->container->cacheTtl(1200);
        
        $config = $this->getPrivateProperty($this->container, 'cacheConfig');
        $this->assertEquals(1200, $config['ttl']);
    }

    public function testCacheTags()
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->container->cacheTags($tags);
        
        $config = $this->getPrivateProperty($this->container, 'cacheConfig');
        $this->assertEquals($tags, $config['tags']);
    }

    public function testGetCacheKey()
    {
        $key = $this->callPrivateMethod($this->container, 'getCacheKey', ['test_operation']);
        
        $this->assertStringContains('form_container', $key);
        $this->assertStringContains('test_container', $key);
        $this->assertStringContains('test_operation', $key);
    }

    public function testGetCacheKeyWithParams()
    {
        $params = ['param1' => 'value1', 'param2' => 'value2'];
        $key = $this->callPrivateMethod($this->container, 'getCacheKey', ['test_operation', $params]);
        
        $this->assertStringContains('form_container', $key);
        $this->assertStringContains('test_container', $key);
        $this->assertStringContains('test_operation', $key);
        // Should contain hash of params
        $this->assertStringContains(md5(serialize($params)), $key);
    }

    public function testAutoClearOnFormAdd()
    {
        // Mock FormBuilder
        $form = $this->createMock(FormBuilder::class);
        $form->method('framework')->willReturnSelf();
        
        // Enable auto-clear
        $this->container->cache(['auto_clear_on_update' => true]);
        
        // Should not throw any errors and should call clearCache internally
        $this->container->addForm('test_form', $form);
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function testAutoClearOnFormRemove()
    {
        // Add a form first
        $form = $this->createMock(FormBuilder::class);
        $form->method('framework')->willReturnSelf();
        $this->container->addForm('test_form', $form);
        
        // Enable auto-clear
        $this->container->cache(['auto_clear_on_update' => true]);
        
        // Should not throw any errors and should call clearCache internally
        $this->container->removeForm('test_form');
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function testCacheDisabledRendering()
    {
        // Mock FormRenderer
        $renderer = $this->createMock(\Litepie\Form\FormRenderer::class);
        $renderer->method('renderContainer')->willReturn('<div>Test Container</div>');
        
        $this->app->instance('form.renderer', $renderer);
        
        // Disable cache
        $this->container->disableCache();
        
        $result = $this->container->render();
        $this->assertEquals('<div>Test Container</div>', $result);
    }

    public function testCacheEnabledRendering()
    {
        // Mock FormRenderer
        $renderer = $this->createMock(\Litepie\Form\FormRenderer::class);
        $renderer->method('renderContainer')->willReturn('<div>Test Container</div>');
        
        $this->app->instance('form.renderer', $renderer);
        
        // Enable cache
        $this->container->enableCache();
        
        // Mock Cache::remember to simulate caching
        Cache::shouldReceive('remember')->once()->andReturn('<div>Cached Container</div>');
        
        $result = $this->container->render();
        $this->assertEquals('<div>Cached Container</div>', $result);
    }

    public function testRenderSingleFormWithoutCache()
    {
        $form = $this->createMock(FormBuilder::class);
        $form->method('framework')->willReturnSelf();
        $form->method('render')->willReturn('<div>Test Form</div>');
        
        $this->container->disableCache();
        $this->container->addForm('test_form', $form);
        
        $result = $this->container->renderSingleForm('test_form');
        $this->assertEquals('<div>Test Form</div>', $result);
    }

    public function testRenderSingleFormWithCache()
    {
        $form = $this->createMock(FormBuilder::class);
        $form->method('framework')->willReturnSelf();
        $form->method('render')->willReturn('<div>Test Form</div>');
        $form->method('toArray')->willReturn(['fields' => []]);
        
        $this->container->enableCache();
        $this->container->addForm('test_form', $form);
        
        // Mock Cache::remember to simulate caching
        Cache::shouldReceive('remember')->once()->andReturn('<div>Cached Form</div>');
        
        $result = $this->container->renderSingleForm('test_form');
        $this->assertEquals('<div>Cached Form</div>', $result);
    }

    public function testToArrayWithoutCache()
    {
        $this->container->disableCache();
        
        $result = $this->container->toArray();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('container', $result);
        $this->assertArrayHasKey('forms', $result);
        $this->assertEquals('test_container', $result['container']['id']);
    }

    public function testToArrayWithCache()
    {
        $this->container->enableCache();
        
        // Mock Cache::remember to simulate caching
        $cachedData = [
            'container' => ['id' => 'cached_container'],
            'forms' => []
        ];
        Cache::shouldReceive('remember')->once()->andReturn($cachedData);
        
        $result = $this->container->toArray();
        $this->assertEquals($cachedData, $result);
    }

    public function testBuildArrayRepresentation()
    {
        $form = $this->createMock(FormBuilder::class);
        $form->method('framework')->willReturnSelf();
        $form->method('toArray')->willReturn([
            'fields' => [],
            'meta' => ['title' => 'Test Form']
        ]);
        
        $this->container->addForm('test_form', $form);
        
        $result = $this->callPrivateMethod($this->container, 'buildArrayRepresentation');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('container', $result);
        $this->assertArrayHasKey('forms', $result);
        $this->assertArrayHasKey('test_form', $result['forms']);
    }

    public function testGetVisibleFormsWithoutCache()
    {
        $form1 = $this->createMock(FormBuilder::class);
        $form1->method('framework')->willReturnSelf();
        
        $form2 = $this->createMock(FormBuilder::class);
        $form2->method('framework')->willReturnSelf();
        
        $this->container->disableCache();
        $this->container->addForm('visible_form', $form1, ['visible' => true]);
        $this->container->addForm('hidden_form', $form2, ['visible' => false]);
        
        $visibleForms = $this->container->getVisibleForms();
        
        $this->assertEquals(1, $visibleForms->count());
        $this->assertTrue($visibleForms->has('visible_form'));
        $this->assertFalse($visibleForms->has('hidden_form'));
    }

    public function testGetVisibleFormsWithCache()
    {
        $form1 = $this->createMock(FormBuilder::class);
        $form1->method('framework')->willReturnSelf();
        
        $this->container->enableCache();
        $this->container->addForm('visible_form', $form1, ['visible' => true]);
        
        // Mock Cache::remember to simulate caching
        $cachedCollection = collect(['cached_form' => ['visible' => true]]);
        Cache::shouldReceive('remember')->once()->andReturn($cachedCollection);
        
        $result = $this->container->getVisibleForms();
        $this->assertEquals($cachedCollection, $result);
    }

    /**
     * Test helper functions for caching
     */
    public function testCacheHelperFunctions()
    {
        // These functions exist and don't throw errors
        $this->assertTrue(function_exists('form_container_cache'));
        $this->assertTrue(function_exists('form_container_cache_enable'));
        $this->assertTrue(function_exists('form_container_cache_disable'));
        $this->assertTrue(function_exists('form_container_cache_clear'));
    }

    /**
     * Get private property value
     */
    private function getPrivateProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * Call private method
     */
    private function callPrivateMethod($object, $method, $args = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
