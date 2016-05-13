<?php
/**************************************************************************
Copyright 2015 Benato Denis

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*****************************************************************************/

namespace Gishiki\tests\Algorithms\Collections;

use Gishiki\Algorithms\Collections\SerializableCollection;
use Gishiki\Algorithms\Collections\GenericCollection;

class SerializationCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Gishiki\Algorithms\Collections\DeserializationException
     */
    public function testBadDeserialization()
    {
        SerializableCollection::deserialize("bad json", SerializableCollection::JSON);
    }
    
    /**
     * @expectedException Gishiki\Algorithms\Collections\DeserializationException
     */
    public function testNotStringJSONDeserialization()
    {
        SerializableCollection::deserialize(9.70, SerializableCollection::JSON);
    }
    
    /**
     * @expectedException Gishiki\Algorithms\Collections\DeserializationException
     */
    public function testBadDeserializator()
    {
        SerializableCollection::deserialize("{---", "this cannot be a valid deserializator!");
    }
    
    public function testCollectionDeserialization() {
        $collection = new SerializableCollection([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ]);
        
        $this->assertEquals($collection, SerializableCollection::deserialize($collection));
    }
    
    public function testCollection()
    {
        $collection = new SerializableCollection();
        $collection->set("a", 1);
        $collection->set("b", 5.50);
        $collection->set("c", "srf");
        $collection->set("e", true);
        $collection->set("f", [1, 2, 3, 4]);
        
        $this->assertEquals([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ], $collection->all());
    }
    
    public function testJSONSerialization()
    {
        $collection = new SerializableCollection([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ]);
        
        $serializationResult = /*json_encode*/($collection->serialize());
        $serialization = json_decode($serializationResult, true);
        
        $this->assertEquals([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ], $serialization);
    }
    
    public function testJSONDeserialization()
    {
        $this->assertEquals(new SerializableCollection([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ]), SerializableCollection::deserialize("{\"a\":1,\"b\":5.5,\"c\":\"srf\",\"e\":true,\"f\":[1,2,3,4]}"));
    }
    
    public function testLoadFromAnotherGenericCollection()
    {
        $collection = new SerializableCollection([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ]);
        
        $this->assertEquals($collection, new SerializableCollection($collection));
    }
    
    public function testLoadFromAnotherSerializableCollection()
    {
        $collection = new GenericCollection([
            "a" => 1,
            "b" => 5.50,
            "c" => "srf",
            "e" => true,
            "f" => [1, 2, 3, 4]
        ]);
        
        $this->assertEquals($collection->all(), (new SerializableCollection($collection))->all());
    }
    
    /**
     * @expectedException Gishiki\Algorithms\Collections\DeserializationException
     */
    public function testBadYamlSerialization()
    {
        $badYaml = 
"x
language:";
        
        SerializableCollection::deserialize($badYaml, SerializableCollection::YAML);
    }
}