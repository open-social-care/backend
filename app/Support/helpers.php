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
