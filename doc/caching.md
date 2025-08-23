# Form Container Caching

The Form Container system now includes comprehensive caching functionality to improve performance by caching rendered forms, container outputs, and data structures.

## Overview

Form caching can significantly improve performance in applications with complex forms or high traffic. The caching system is designed to be:
- **Flexible**: Multiple cache drivers supported
- **Automatic**: Auto-clears on form modifications
- **Configurable**: Fine-grained control over what gets cached
- **Tagged**: Uses cache tags for efficient bulk clearing

## Configuration

### Basic Configuration

Add caching configuration to your `config/form.php` file:

```php
'cache' => [
    'enabled' => env('FORM_CACHE_ENABLED', true),
    'ttl' => env('FORM_CACHE_TTL', 3600), // 1 hour
    'driver' => env('FORM_CACHE_DRIVER', null), // null = default cache driver
    'prefix' => env('FORM_CACHE_PREFIX', 'form_cache'),
    'tags' => [
        'forms',
        'containers',
    ],
    'auto_clear_on_update' => true,
    'cache_single_forms' => true,
    'cache_container_renders' => true,
    'cache_form_arrays' => true,
    'cache_visible_forms' => true,
],
```

### Environment Variables

Add these to your `.env` file:

```env
FORM_CACHE_ENABLED=true
FORM_CACHE_TTL=3600
FORM_CACHE_DRIVER=redis
FORM_CACHE_PREFIX=form_cache
```

## Usage

### Basic Caching

```php
// Create a container with default caching
$container = Form::container('user-settings')
    ->name('User Settings');

// Caching is automatically applied to:
// - render() method
// - renderSingleForm() method
// - toArray() method
// - getVisibleForms() method
```

### Manual Cache Configuration

```php
// Configure caching manually
$container = Form::container('user-settings')
    ->cache([
        'enabled' => true,
        'ttl' => 1800, // 30 minutes
        'tags' => ['user_forms', 'settings'],
    ]);

// Or use fluent methods
$container = Form::container('user-settings')
    ->enableCache(1800) // Enable with 30 min TTL
    ->cacheTags(['user_forms', 'settings']);
```

### Cache Control Methods

```php
// Enable caching
$container->enableCache(3600); // 1 hour TTL

// Disable caching
$container->disableCache();

// Set cache TTL
$container->cacheTtl(1800); // 30 minutes

// Set cache tags
$container->cacheTags(['forms', 'user_settings']);

// Clear cache for this container
$container->clearCache();
```

### Helper Functions

```php
// Configure cache using helpers
form_container_cache($container, [
    'ttl' => 1800,
    'tags' => ['user_forms']
]);

// Enable cache with helpers
form_container_cache_enable($container, 3600);

// Disable cache
form_container_cache_disable($container);

// Clear cache
form_container_cache_clear($container);
```

## What Gets Cached

### Container Rendering

The `render()` method caches the complete rendered HTML output:

```php
$container = Form::container('settings');
// ... add forms ...

// First call: generates and caches HTML
$html1 = $container->render();

// Second call: returns cached HTML
$html2 = $container->render(); // Much faster!
```

### Single Form Rendering

Individual form rendering is also cached:

```php
// First call: generates and caches
$html1 = $container->renderSingleForm('personal');

// Second call: returns cached HTML
$html2 = $container->renderSingleForm('personal');
```

### Array Representation

The `toArray()` method caches the array representation:

```php
// First call: generates and caches array
$array1 = $container->toArray();

// Second call: returns cached array
$array2 = $container->toArray();
```

### Visible Forms Collection

The `getVisibleForms()` method caches filtered collections:

```php
// First call: filters and caches
$visible1 = $container->getVisibleForms();

// Second call: returns cached collection
$visible2 = $container->getVisibleForms();
```

## Cache Keys

Cache keys are automatically generated based on:

- Container ID
- Operation type (render, toArray, etc.)
- Form configuration hash
- Display mode (tabbed, accordion, stacked)
- Framework setting
- Form count and structure

Example cache keys:
```
form_cache:user_settings_123:render:abc123def456
form_cache:user_settings_123:render_single:personal:xyz789abc123
form_cache:user_settings_123:to_array:def456ghi789
```

## Automatic Cache Invalidation

Cache is automatically cleared when:

1. **Forms are added** to the container
2. **Forms are removed** from the container
3. **Container settings change** (framework, display mode, etc.)
4. **Forms are modified** (if auto_clear_on_update is enabled)

```php
$container = Form::container('settings');

// Add a form - cache is automatically cleared
$container->createForm('profile', ['title' => 'Profile']);

// Remove a form - cache is automatically cleared
$container->removeForm('profile');

// Change framework - cache is automatically cleared
$container->framework('tailwind');
```

## Manual Cache Management

### Clear All Container Cache

```php
// Clear all cache for this container
$container->clearCache();
```

### Clear Cache with Tags

If you're using cache tags:

```php
// Clear all cached items with specific tags
Cache::tags(['forms', 'user_settings'])->flush();

// Or using the container's configured tags
$container->clearCache(); // Uses container's tags automatically
```

### Clear Specific Cache

```php
// Clear specific cache keys manually
Cache::forget('form_cache:user_settings:render:abc123');
```

## Performance Tips

### 1. Use Appropriate TTL

Set cache TTL based on how often your forms change:

```php
// For frequently changing forms
$container->cacheTtl(300); // 5 minutes

// For stable forms
$container->cacheTtl(86400); // 24 hours
```

### 2. Use Cache Tags

Cache tags make it easier to clear related caches:

```php
$container->cacheTags(['user_forms', 'settings', 'profile']);

// Clear all user form caches at once
Cache::tags(['user_forms'])->flush();
```

### 3. Choose the Right Cache Driver

For better performance with large forms:

```php
// Use Redis for better performance
'cache' => [
    'driver' => 'redis',
    // ... other options
],
```

### 4. Selective Caching

Disable caching for frequently changing containers:

```php
// For dynamic forms that change often
$dynamicContainer = Form::container('dynamic-form')
    ->disableCache();
```

## Advanced Usage

### Custom Cache Configuration

```php
class CustomFormContainer extends \Litepie\Form\FormContainer
{
    protected function initializeCache(): void
    {
        // Custom cache configuration
        $this->cacheConfig = [
            'enabled' => true,
            'ttl' => 7200, // 2 hours
            'driver' => 'redis',
            'prefix' => 'custom_forms',
            'tags' => ['custom', 'forms', $this->getId()],
            'auto_clear_on_update' => false, // Manual cache management
        ];
        
        parent::initializeCache();
    }

    public function addFormWithCache(string $key, FormBuilder $form): self
    {
        $this->addForm($key, $form);
        
        // Custom cache clearing logic
        if ($this->shouldClearCache($key)) {
            $this->clearCache();
        }
        
        return $this;
    }

    protected function shouldClearCache(string $formKey): bool
    {
        // Custom logic to determine if cache should be cleared
        return in_array($formKey, ['critical_form', 'frequently_updated']);
    }
}
```

### Conditional Caching

```php
$container = Form::container('user-profile');

// Only enable caching in production
if (app()->environment('production')) {
    $container->enableCache(3600);
} else {
    $container->disableCache();
}
```

### Cache Warming

```php
// Pre-warm cache for better performance
class FormCacheWarmer
{
    public function warmUserSettingsForms(User $user): void
    {
        $container = $this->buildUserSettingsContainer($user);
        
        // Warm the cache by calling methods
        $container->render(); // Caches render output
        $container->toArray(); // Caches array representation
        $container->getVisibleForms(); // Caches visible forms
        
        // Warm single form caches
        foreach ($container->getForms() as $key => $form) {
            $container->renderSingleForm($key);
        }
    }
}
```

## Monitoring and Debugging

### Check Cache Status

```php
// Check if caching is enabled
if ($container->isCacheEnabled()) {
    echo "Caching is enabled";
}

// Get cache configuration
$cacheConfig = $container->getCacheConfig();
```

### Debug Cache Keys

```php
// Get cache key for debugging
$key = $container->getCacheKey('render', ['debug' => true]);
echo "Cache key: " . $key;
```

### Cache Statistics

```php
// Monitor cache hit rates (requires appropriate cache driver)
$stats = Cache::getMemcached()->getStats();

// Or use Laravel's cache events
Event::listen('cache:hit', function ($key, $value) {
    Log::info("Cache hit: {$key}");
});

Event::listen('cache:missed', function ($key) {
    Log::info("Cache miss: {$key}");
});
```

## Best Practices

1. **Enable caching in production** - Significant performance benefits
2. **Use cache tags** - Makes cache management easier
3. **Set appropriate TTL** - Balance between performance and freshness
4. **Monitor cache usage** - Ensure cache is working effectively
5. **Test cache invalidation** - Verify cache clears when forms change
6. **Use Redis for high traffic** - Better performance than file cache
7. **Implement cache warming** - Pre-populate cache for critical forms
8. **Handle cache failures gracefully** - Forms should work even if cache fails

## Troubleshooting

### Cache Not Working

1. Check if caching is enabled:
   ```php
   if (!$container->isCacheEnabled()) {
       $container->enableCache();
   }
   ```

2. Verify cache driver is working:
   ```php
   Cache::put('test', 'value', 60);
   if (Cache::get('test') !== 'value') {
       // Cache driver issue
   }
   ```

3. Check cache tags support:
   ```php
   // Some cache drivers don't support tags
   if (!Cache::supportsTags()) {
       // Use cache without tags
       $container->cacheTags([]);
   }
   ```

### Cache Not Clearing

1. Verify auto-clear is enabled:
   ```php
   $container->cache(['auto_clear_on_update' => true]);
   ```

2. Manual cache clearing:
   ```php
   $container->clearCache();
   Cache::tags(['forms'])->flush();
   ```

### Performance Issues

1. Check TTL is not too high:
   ```php
   $container->cacheTtl(3600); // Reduce if needed
   ```

2. Use appropriate cache driver:
   ```php
   // Switch to Redis for better performance
   $container->cache(['driver' => 'redis']);
   ```

The caching system provides excellent performance improvements while maintaining the flexibility and ease of use of the Form Container system.
