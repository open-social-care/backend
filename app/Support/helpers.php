<?php

/**
 * All files in this folder will be included in the application.
 */
if (! function_exists('current_user')) {
    /**
     * Returns an instance of the current user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    function current_user(): Illuminate\Contracts\Auth\Authenticatable
    {
        return auth()->user();
    }
}
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
