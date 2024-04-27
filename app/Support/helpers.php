<?php

if (!function_exists('is_enum')) {
    function is_enum($enum): bool
    {
        if (!is_object($enum)) {
            return false;
        }

        $class = get_class($enum);

        try {
            $reflection = new ReflectionClass($class);
        } catch (Throwable) {
            return false;
        }

        return $reflection->isEnum();
    }
}
