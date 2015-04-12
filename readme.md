# Phapi NullCache

Phapi NullCache is a fallback cache package that acts like a working cache but it really doesn't. The benefit is that if no other cache has been configured the NullCache will be used. Every time the cache is called in the code the NullCache will act like a real cache so that the application doesn't break.

Phapi has one important rule regarding cache: A working cache should **not** be a requirement for the application to work. So if Phapi is unable to connect to the cache backend it wont stop the execution. Instead the configured cache will be replaced with a dummy cache, <code>new NullCache()</code>.

## General cache usage
```php
<?php
// Add something to the cache
$cache->set('test', 'value');

// Read something from the cache
echo $cache->get('test'); // Will echo "value"

// Check if something exists in the cache
$bool = $cache->has('test');

// Remove from cache
$cache->clear('test');

// Flush the cache
$cache->flush();
```

## Implement a new Cache package
There are two simple rules when implementing a new cache package:

1. Implement the <code>Phapi\Contract\Cache</code> interface. The interface implies that 5 methods are implemented:
  * <code>set($key, $value)</code> - Save the key and value to the cache
  * <code>get($key)</code> - Get the value for the key
  * <code>has($key)</code> - Check if key exists (return boolean)
  * <code>clear($key)</code> - Remove the key from the cache
  * <code>flush()</code> - Clear the cache
2. The <code>__construct</code> should connect to the cache backend and throw an exception if it fails to connect. If an exception is thrown the NullCache will be used instead.


## License
Phapi NullCache is licensed under the MIT License - see the LICENSE file for details

## Contribute
Contribution, bug fixes etc are [always welcome](https://github.com/phapi/cache-nullcache/issues/new).
