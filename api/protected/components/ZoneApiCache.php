<?php

/**
 * Cache components
 *
 * @author  huytbt <huytbt@gmail.com>
 * @version Beta 1.0
 */
class ZoneApiCache
{
    /**
     * Retrieve cache
     *
     * @param string $category category
     * @param string $key      key
     *
     * @return mix
     */
    public static function get($category, $key)
    {
        return Yii::app()->cache->get("{$category}_{$key}");
    }

    /**
     * Cache
     *
     * @param string $category category
     * @param string $key      key
     * @param mix    $value    value
     *
     * @return void
     */
    public static function cache($category, $key, $value)
    {
        Yii::app()->cache->set("{$category}_{$key}", $value);
    }

    /**
     * Release cache
     *
     * @param string $category category
     * @param string $key      key
     *
     * @return void
     */
    public static function release($category, $key)
    {
        Yii::app()->cache->delete("{$category}_{$key}");
    }

    /**
     * Index cache
     *
     * @param string $category      category
     * @param string $key           key
     * @param string $indexCategory indexCategory
     * @param string $indexKey      indexKey
     *
     * @return void
     */
    public static function index($category, $key, $indexCategory, $indexKey)
    {
        $oldValue = self::get($category, $key);
        if (empty($oldValue) || !is_array($oldValue)) {
            $oldValue = array();
        }

        $oldValue[] = array(
            'category'  => $indexCategory,
            'key'       => $indexKey,
        );

        self::cache($category, $key, $oldValue);
    }

    /**
     * Release cache
     *
     * @param string $category category
     * @param string $key      key
     *
     * @return void
     */
    public static function releaseIndexes($category, $key)
    {
        $indexes = self::get($category, $key);
        if (empty($indexes) || !is_array($indexes)) {
            $indexes = array();
        }

        foreach ($indexes as $index) {
            self::release($index['category'], $index['key']);
        }

        self::release($category, $key);
    }
}