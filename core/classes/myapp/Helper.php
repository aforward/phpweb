<?php

namespace MyApp;

use MyApp\Config;

class Helper
{

    /**
     * Convert a $rawInput search string and format it for a SQL search.
     * If the search string already has %, then leave it alone,
     * Otherwise wrap it with %$rawInput%
     * @param string $rawInput The search string to be made searchable
     * @return string The formatted string, ready for search
     */
    public static function makeSearchable($rawInput)
    {
        if (!$rawInput) {
            return "%";
        } elseif (strpos($rawInput, '%') !== false) {
            return $rawInput;
        } else {
            return "%$rawInput%";
        }
    }

    /**
     * Convert a $raw search string into an integer if it looks like one
     * Otherwise, return null.
     * @param string $raw A integer represented as a string
     * @return int The string as an int, otherwise null
     */
    public static function parseInt($raw)
    {
        $asInt = intVal($raw);
        return $raw && $asInt == $raw ? $asInt : null;
    }
}
