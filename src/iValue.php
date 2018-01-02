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

/**
 * Interface iValue
 * @package eppak\Value
 */
interface iValue
{
    /**
     * @param $name
     * @return mixed
     */
    function has($name);

    /**
     * @return mixed
     */
    function duplicate();

    /**
     * @return mixed
     */
    function toJson();

    /**
     * @param $name
     * @param $chain
     * @param bool $ex
     * @return mixed
     */
    function hasChain($name, $chain, $ex = false);

    /**
     * @param $name
     * @param $chain
     * @param bool $ex
     * @return mixed
     */
    function getChainValue($name, $chain, $ex = false);

    /**
     * @param $json
     * @return mixed
     */
    static function fromJson($json);

    /**
     * @param $arr
     * @return mixed
     */
    static function fromArray($arr);
}