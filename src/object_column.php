<?php

namespace Cryonighter\ObjectColumn;

use ArrayAccess;
use RuntimeException;

/**
 * @link https://github.com/cryonighter/object-column
 *
 * @param iterable    $objects
 * @param string|null $columnKey
 * @param string|null $indexKey
 *
 * @return array
 */
function object_column(iterable $objects, ?string $columnKey = null, ?string $indexKey = null): array
{
    $columnResolver = function ($object, string $columnPath) use (&$columnResolver) {
        $columnChain = explode('.', $columnPath, 2);
        $columnName = array_shift($columnChain);

        if (isset($object->$columnName)) {
            $value = $object->$columnName;
        } elseif (is_object($object)) {
            $getter = 'get' . ucfirst($columnName);
            $hasser = 'has' . ucfirst($columnName);
            $isser = 'is' . ucfirst($columnName);

            if (method_exists($object, $getter)) {
                $value =  $object->$getter();
            } elseif (method_exists($object, $hasser)) {
                $value = $object->$hasser();
            } elseif (method_exists($object, $isser)) {
                $value = $object->$isser();
            } elseif (method_exists($object, $columnName)){
                $value = $object->$columnName();
            } else {
                throw new RuntimeException("Column key '$columnName' not found in class " . get_class($object));
            }
        } elseif (is_array($object) && array_key_exists($columnName, $object)) {
            $value =  $object[$columnName];
        } elseif ($object instanceof ArrayAccess && $object->offsetExists($columnName)) {
            $value =  $object->offsetGet($columnName);
        } else {
            throw new RuntimeException("Is not array or object");
        }

        if (!$columnChain) {
            return $value;
        }

        return $columnResolver($value, current($columnChain));
    };

    $result = [];

    foreach ($objects as $object) {
        if ($columnKey !== null && $columnKey !== '') {
            $value = $columnResolver($object, $columnKey);
        } else {
            $value = $object;
        }

        if ($indexKey !== null && $indexKey !== '') {
            $result[$columnResolver($object, $indexKey)] = $value;
        } else {
            $result[] = $value;
        }
    }

    return $result;
}
