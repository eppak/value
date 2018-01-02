<?php

use PHPUnit\Framework\TestCase;

use eppak\Value\Value as Value;
use eppak\Value\PropertyNotFound as PropertyNotFound;
use eppak\Value\PropertyNotWritable as PropertyNotWritable;

class caseA extends Value
{

    public function __construct()
    {
        parent::__construct([
            'testRead' => 'read',
            'testWrite' => 'write',
            'testReadWrite' => 'readwrite',
            'undisciplined' => 'readwrite'
        ], [
            'testRead' => static::R,
            'testWrite' => static::RW,
            'testReadWrite' => static::RW,
        ]);
    }
}

class transportTest extends TestCase
{
    public function testCaseAReadOnly()
    {
        $case = new caseA();
        $this->expectException(PropertyNotWritable::class);
        $this->assertTrue($case->testRead == 'read');
        $case->testRead = 'written';
    }

    public function testCaseAUndisciplined()
    {
        $case = new caseA();
        $this->assertTrue($case->undisciplined == 'readwrite');
        $case->undisciplined = 'written';
        $this->assertTrue($case->undisciplined == 'written');
    }

    public function testCaseANotExistentRead()
    {
        $this->expectException(PropertyNotFound::class);
        $case = new caseA();
        $read = $case->undefined;
    }

    public function testCaseANotExistentWrite()
    {
        $this->expectException(PropertyNotFound::class);
        $case = new caseA();
        $case->undefined = 'written';
    }

    public function testCaseAHas()
    {
        $case = new caseA();
        $this->assertTrue($case->has('testWrite'));
        $this->assertFalse($case->has('undefined'));
    }

    public function testCaseAToJson()
    {
        $case = new caseA();
        $this->assertTrue(json_decode($case->toJson(), true)['testRead'] == 'read');
    }

    public function testCaseFromJson()
    {
        $case = Value::fromJson('{"test": "read"}');

        $this->assertTrue($case->test == 'read');
    }

    public function testCaseFromJsonArrayA()
    {
        $case = Value::fromJson('{"test": {"item": {"a": 1, "b": 2}}}');

        foreach ($case->test as $key => $value) {
            $this->assertTrue(count($case->test{$key}) == 2);

            foreach ($case->test{$key} as $item => $value) {
                if ($item == 'a') {
                    $this->assertTrue($value == 1);
                }
                if ($item == 'b') {
                    $this->assertTrue($value == 2);
                }
            }
        }
    }

    public function testCaseFromJsonArrayB()
    {
        $case = Value::fromJson('{"test": [{"a": 1, "b": 2}, {"c": 3, "d": 4}]}');

        $this->assertTrue(count($case->test) == 2);
        foreach ($case->test as $item) {
            $this->assertTrue(count($item) == 2);
            foreach ($item as $key => $value) {
                if ($key == 'a') {
                    $this->assertTrue($value == 1);
                }
                if ($key == 'b') {
                    $this->assertTrue($value == 2);
                }
                if ($key == 'c') {
                    $this->assertTrue($value == 3);
                }
                if ($key == 'd') {
                    $this->assertTrue($value == 4);
                }
            }
        }
    }

    public function testCaseClone()
    {
        $case = new caseA();
        $new = $case->duplicate();

        $this->assertTrue($case->testRead == $new->testRead);
    }

    public function testCaseGetters()
    {
        $case = new caseA();

        $this->assertTrue($case->testRead == $case->gettestRead());
        $this->assertTrue($case->testRead == $case->getTestRead());
    }

    public function testJsonExists()
    {
        $json = Value::fromJson('{ "test": { "test1" : { "test2": { "test3" : 1} } }}');

        $this->assertTrue($json->hasChain('test', 'test1>test2>test3'));
        $this->assertTrue($json->getChainValue('test', 'test1>test2>test3') == 1);
    }
}
