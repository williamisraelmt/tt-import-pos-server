<?php
/**
 * This functions comes from: https://github.com/grohiro/laravel-camelcase-json.git
 */

namespace App\Utils;

class CamelCaseUtil
{

    public static function toCamelCase ($string) {
        $string_ = str_replace(' ', '', ucwords(str_replace('_',' ', $string)));
        return lcfirst($string_);
    }

    public static function toUnderscore ($string) {
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $string));
    }

    public static function transformKeys($transform, &$array) {
        foreach (array_keys($array) as $key):
            # Working with references here to avoid copying the value,
            # since you said your data is quite large.
            $value = &$array[$key];

            unset($array[$key]);
            # This is what you actually want to do with your keys:
            #  - remove exclamation marks at the front
            #  - camelCase to snake_case
            $transformedKey = call_user_func($transform, $key);
            # Work recursively
            if (is_array($value)) {
                self::transformKeys($transform, $value);
            } else if (is_object($value)){
                $value = (array)$value;
                self::transformKeys($transform, $value);
            }
            # Store with new key
            $array[$transformedKey] = $value;
            # Do not forget to unset references!
            unset($value);
        endforeach;
    }

    public static function keysToCamelCase ($array) {

        if (is_object($array)){
            $array = (array) $array;
        } else if (is_string($array)) {
            $array = json_decode($array);
        }

        self::transformKeys(['self', 'toCamelCase'], $array );
        return $array;
    }

    public static function keysToUnderscore ($array) {
        if (is_object($array)){
            $array = (array) $array;
        } else if (is_string($array)) {
            $array = json_decode($array);
        }

        self::transformKeys(['self', 'toUnderscore'], $array);
        return $array;
    }

}
