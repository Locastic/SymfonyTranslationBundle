<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Utils;

use Symfony\Component\PropertyAccess\PropertyAccessor;

use function explode;

final class ArrayUtils
{
    public static function arrayFlatten(array $translations, string $prefix = ''): array
    {
        $result = array();

        foreach ($translations as $key => $value) {
            $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

            if (is_array($value)) {
                $result = array_merge_recursive($result, self::arrayFlatten($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

    public static function recursiveKsort(array &$array): bool
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::recursiveKsort($value);
            }
        }

        return ksort($array);
    }

    public static function keyToArray(string $translationKey, string $value): array
    {
        $result = [];
        $path = '';
        $explodedKey = explode('.', $translationKey);
        foreach ($explodedKey as $key) {
            if (empty($key)) {
                continue;
            }
            $path .= '[' . $key . ']';
        }
        $propertyAccessor = new PropertyAccessor();
        $propertyAccessor->setValue($result, $path, $value);

        return $result;
    }
}
