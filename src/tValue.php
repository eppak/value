<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2017 Alessandro Cappellozza (alessandro.cappellozza@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace eppak\Value;

trait tValue
{
    /**
     * @param $array
     * @param $key
     * @return \ArrayObject
     */
    private function getItem($array, $key)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return null;
        }
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    private function startsWith($haystack, $needle)
    {
        return (substr($haystack, 0, strlen($needle)) === $needle);
    }

    /**
     * @param $name
     * @param $prefix
     * @return bool|string
     */
    private function normalizeProperty($name, $prefix)
    {
        $property = substr($name, strlen($prefix));
        if (ctype_upper($property[0]) && !$this->has($property)) {
            $property = strtolower($property[0]) . substr($property, 1);
        }

        return $property;
    }

    /**
     * @param $name
     * @param $chain
     * @return array
     * @throws \Exception
     */
    private function getChain($name, $chain)
    {
        if (!$this->has($name)) {
            throw new PropertyNotFound("Unknown property {$name}");
        }

        $data = $this->data[$name];
        $chain = explode(static::JSON_DELIMITER, $chain);
        if (gettype($data) == 'array') {
            $subObject = $data;
            foreach ($chain as $key) {
                if (array_key_exists($key, $subObject)) {
                    $subObject = $subObject[$key];
                } else {
                    return ['success' => false, 'value' => null];
                }
            }
            return ['success' => true, 'value' => $subObject];
        } else {
            throw new \Exception("Value {$name} must be an Array");
        }
    }
}