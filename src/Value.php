<?php namespace eppak\Value;

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

include("Exceptions.php");

/**
 * Class Value
 * @version 1.0.0.0
 * @author Alessandro Cappellozza (alessandro.cappellozza@gmail.com)
 * @package eppak\Value
 */
class Value implements iValue
{
    use tValue;
    /**
     * Parameters
     */
    const SET = 'set';
    const GET = 'get';
    const R = 'r';
    const RW = 'rw';
    const ALL = '*';
    const JSON_DELIMITER = '>';

    protected $data = [];
    protected $discipline = ['*' => self::RW];

    /**
     * Value constructor.
     * @param null $data
     * @param null $discipline
     */
    public function __construct($data = null, $discipline = null)
    {
        if ($data != null) {
            $this->data = $data;
        }
        if ($discipline != null) {
            $this->discipline = $discipline;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    protected function canRead($name)
    {
        if ($this->getItem($this->discipline, static::ALL) == static::R ||
            $this->getItem($this->discipline, static::ALL) == static::RW) {
            return true;
        }

        return $this->getItem($this->discipline, $name) == static::R ||
            $this->getItem($this->discipline, $name) == static::RW ||
            $this->getItem($this->discipline, $name) == null;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function canWrite($name)
    {
        if ($this->getItem($this->discipline, static::ALL) == static::RW) {
            return true;
        }

        return $this->getItem($this->discipline, $name) == static::RW ||
               $this->getItem($this->discipline, $name) == null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @param $json
     * @return Value
     */
    public static function fromJson($json)
    {
        return new Value(json_decode($json, true));
    }

    /**
     * @param $arr
     * @return Value
     */
    public static function fromArray($arr)
    {
        return new Value($arr);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data);
    }

    /**
     * @return Value
     */
    public function duplicate()
    {
        return new Value($this->data, $this->discipline);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->has($name)) {
            throw new PropertyNotFound("Unknown property {$name}");
        }
        if (!$this->canRead($name)) {
            throw new PropertyNotReadable("Propperty {$name} is not readable");
        }

        return $this->data[$name];
    }

    /**
     * @param $name
     * @param $value
     * @throws PropertyNotFound
     * @throws PropertyNotWritable
     */
    public function __set($name, $value)
    {
        if (!$this->has($name)) {
            throw new PropertyNotFound("Unknown property {$name}");
        }
        if (!$this->canWrite($name)) {
            throw new PropertyNotWritable("Propperty {$name} is not writable");
        }

        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @param $args
     * @return mixed|void
     */
    public function __call($name, $args)
    {

        if ($this->startsWith($name, static::GET)) {
            if (count($args) > 0) {
                throw new \InvalidArgumentException("No arguments expected");
            }
            return $this->{$this->normalizeProperty($name, static::GET)};
        }

        if ($this->startsWith($name, static::SET)) {
            if (count($args) != 1) {
                throw new \InvalidArgumentException("Expected only one argument");
            }
            $this->{$this->normalizeProperty($name, static::SET)} = $args[0];
            return;
        }

        throw new \BadFunctionCallException("Unknown method {$name}");
    }

    /**
     * @param $name
     * @param $chain
     * @param bool $ex
     * @return mixed
     * @throws \Exception
     */
    public function hasChain($name, $chain, $ex = false)
    {
        if ($ex) {
            throw new \Exception("Chain {$chain} not found");
        } else {
            return $this->getChain($name, $chain)['success'];
        }
    }

    /**
     * @param $name
     * @param $chain
     * @param bool $ex
     * @return array|null
     * @throws \Exception
     */
    public function getChainValue($name, $chain, $ex = false)
    {
        $value = $this->getChain($name, $chain);
        if ($value['success']) {
            return $value['value'];
        } else {
            if ($ex) {
                throw new \Exception("Chain value {$chain} not found");
            } else {
                return null;
            }
        }
    }
}