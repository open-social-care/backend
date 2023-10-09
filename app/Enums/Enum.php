<?php

namespace App\Enums;

interface Enum
{
    /**
     * To return array of names values
     *
     * @return array
     */
    public static function getCasesNameValues(): array;

    /**
     * To return translated value.
     *
     * @param  string  $value
     */
    public static function trans(string $value): string;
}
