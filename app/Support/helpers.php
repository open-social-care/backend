<?php

/**
 * Format a collection for selection/array with key and label
 */
if (! function_exists('to_select')) {

    /**
     * Returns array for to select options for model
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param $key
     * @param $value
     * @return array
     */
    function to_select(Illuminate\Database\Eloquent\Collection $collection, $key = 'id', $value = 'name'): array
    {
        return $collection->map(function ($model) use ($key, $value) {
            return [$key => $model[$key], $value => $model[$value]];
        })->toArray();
    }
}
/**
 * Format a collection for selection/array with key and label based on enum class
 */
if (! function_exists('to_select_by_enum')) {

    /**
     * Returns array for to select options for model by trans enum
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param string $enumClass
     * @param $key
     * @param $value
     * @return array
     */
    function to_select_by_enum(Illuminate\Database\Eloquent\Collection $collection, string $enumClass, $key = 'id', $value = 'name'): array
    {
        return $collection->map(function ($model) use ($key, $value, $enumClass) {
            return [$key => $model[$key], $value => $enumClass::trans($model[$value])];
        })->toArray();
    }
}
