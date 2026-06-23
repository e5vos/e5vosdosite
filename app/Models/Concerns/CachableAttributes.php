<?php

namespace App\Models\Concerns;

use Closure;

/**
 * Local replacement for astrotomic/laravel-cachable-attributes (capped at Laravel 10).
 * Marks an Eloquent model whose attribute accessors can be cached.
 *
 * @see CachesAttributes
 */
interface CachableAttributes
{
    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     */
    public function remember(string $key, ?int $ttl, Closure $callback);

    /**
     * Get an item from the cache, or execute the given Closure and store the result forever.
     */
    public function rememberForever(string $key, Closure $callback);

    /**
     * Remove an item from the cache.
     */
    public function forget(string $key): bool;

    /**
     * Remove all items from the cache.
     */
    public function flush(): bool;
}
