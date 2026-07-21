<?php
/**
 * KT Framework Object Cache
 * InfinityFree optimized
 */

if (!defined('ABSPATH'))
    exit;

class KT_Cache
{

    public static function flush_runtime()
    {
        self::$runtime = [];
    }

    private static $runtime = [];

    /**
     * Get cache
     */
    public static function get($key, $group = 'kt')
    {
        $runtime_key = $group . ':' . $key;

        if (isset(self::$runtime[$runtime_key])) {
            return self::$runtime[$runtime_key];
        }

        $value = wp_cache_get($key, $group);

        if ($value !== false) {
            self::$runtime[$runtime_key] = $value;
        }

        return $value;
    }

    /**
     * Set cache
     */
    public static function set($key, $value, $group = 'kt', $ttl=0)
    {
        $runtime_key = $group . ':' . $key;

        self::$runtime[$runtime_key] = $value;

        wp_cache_set($key, $value, $group, $ttl);
    }

    /**
     * Delete single cache
     */
    public static function delete($key, $group = 'kt')
    {
        $runtime_key = $group . ':' . $key;

        unset(self::$runtime[$runtime_key]);

        wp_cache_delete($key, $group);
    }

    /**
     * Flush entire group (NEW)
     */
    public static function flush_group($group = 'kt')
    {
        /**
         * Remove runtime cache
         */
        foreach (self::$runtime as $runtime_key => $value) {

            if (strpos($runtime_key, $group . ':') === 0) {

                unset(self::$runtime[$runtime_key]);

            }

        }

        /**
         * Flush persistent object cache if available
         */
        if (function_exists('wp_cache_flush_group')) {

            wp_cache_flush_group($group);

        } else {

            /**
             * fallback: flush entire cache
             * safe for InfinityFree
             */
            wp_cache_flush();

        }

    }

}