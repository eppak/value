Intro
====
This is a smart dto object, is useful to transport and manipulate data. Usually a value object
have getter and setter for a bunch of properties that represent data. 

Installation
====
```sh
# composer install eppak/value
```

Constructor
====
Can be created in various modes, it accept usually an array of fields with values;
the second optional parameter represent if property can be written or is readonly. 

  - As new.
  - From an array.
  - From JSON
  
If the object need to be readonly you can istantiate a **roValue** object istead ov **Value**.

Example of Initialization
====
```php
<?php
    use eppak\value;
    
    //...
    
    $data = [ 'testRead' => 'read',
              'testWrite' => 'write',
              'testReadWrite' => 'readwrite',
              'undisciplined' => 'readwrite' ];
    
    $value = new Value( $data );
    $value = new Value( $data, [ 'testRead' => static::R,
                                 'testWrite' => static::W,
                                 'testReadWrite' => static::RW ] );    

    $value = Value::fromArray( $data );
    $value = Value::fromJson( '{"test": "read"}' );
```

Example of reading/writing
====
```php
<?php
    $testRead =  $value->getTestRead();
    $testReadWrite =  $value->getTestReadWrite();
    
    $value->setTestReadWrite('some value');
    $value->setTestRead('some value'); // Thrown an error, is read only    
```

Testing property existence
====
Properties existence can be probed as simple level or multi level, useful when value object is istanced from JSON.
```php
<?php
    $json = Json::fromJson('{ "test": { "test1" : { "test2": { "test3" : 1} } }}');
    $testRead_present = $json->has('test');
    $testChin_present = $json->hasChain('test', 'test1>test2>test3');
```

Cheat sheet
====
| Method        | Type           | Description  |
| ------------- |-------------| -----|
| fromArray    | static | *Create an object from array* |
| fromJson    | static | *Create an object from a json string* |
| has    | virtual | *Check if a property is present* |
| hasChain    | virtual | *Check if a chain of properties is present* |
| getChainValue    | virtual | *Get the value of a chain of properties* |
| duplicate    | virtual | *Create a new object identical to the current* |
| getX    | virtual | *Get the X property* |
| setX    | virtual | *Set the X property* |

License
====
MIT
