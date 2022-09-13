<?php

namespace ElielFC\JsonToClass\Test\Unit;

use ElielFC\JsonToClass\{
    Contracts\JsonToClass,
    BaseJsonToClass
};
use PHPUnit\Framework\TestCase;

class JsonToClassTest extends TestCase
{
    /**
     * Teste o mapeamento de um array para class
     *
     * @return void
     * @test
     */
    public function array_to_class()
    {
        $entity = new class extends BaseJsonToClass implements JsonToClass {
            public int $id;
            public array $keyArray;
            public bool $keyBool;
            public ?int $keyNull;
            public float $keyFloat;
            public EntityJsonToClass $keyClass;
            public array $keyArrayClass;
            public bool $keyWithMutator;

            protected array $cast = [
                'keyClass' => EntityJsonToClass::class,
                'keyArrayClass' => EntityJsonToClass::class
            ];

            public function setKeyWithMutatorAttribute(mixed $value): void
            {
                $this->keyWithMutator = $value === "VERDADEIRO";
            }
        };

        $entity->make([
            'id' => 1,
            'keyFalse' => 'teste',
            'keyArray' => [
                'id' => 10,
                'name' => 'name'
            ],
            'keyBool' => false,
            'keyNull' => null,
            'keyFloat' => 4.2,
            'keyClass' => [
                'id' => 1,
                'active' => true
            ],
            'keyArrayClass' => [
                [
                    'id' => 20,
                    'active' => false
                ],
                [
                    'id' => 21,
                    'active' => true
                ]
                ],
            'keyWithMutator' => "VERDADEIRO"
        ]);

        $this->assertTrue(property_exists($entity, 'id'));
        $this->assertIsInt($entity->id);
        $this->assertEquals(1, $entity->id);

        $this->assertFalse(property_exists($entity, 'keyFalse'));

        $this->assertTrue(property_exists($entity, 'keyArray'));
        $this->assertIsArray($entity->keyArray);
        $this->assertEquals([
            'id' => 10,
            'name' => 'name'
        ], $entity->keyArray);

        $this->assertTrue(property_exists($entity, 'keyBool'));
        $this->assertIsBool($entity->keyBool);
        $this->assertEquals(false, $entity->keyBool);

        $this->assertTrue(property_exists($entity, 'keyFloat'));
        $this->assertIsFloat($entity->keyFloat);
        $this->assertEquals(4.2, $entity->keyFloat);

        $this->assertTrue(property_exists($entity, 'keyNull'));
        $this->assertEquals(null, $entity->keyNull);

        $this->assertTrue(property_exists($entity, 'keyClass'));
        $this->assertTrue($entity->keyClass instanceof EntityJsonToClass);
        $this->assertTrue(property_exists($entity->keyClass, 'active'));
        $this->assertTrue(property_exists($entity->keyClass, 'id'));
        $this->assertEquals(true, $entity->keyClass->active);
        $this->assertEquals(1, $entity->keyClass->id);

        $this->assertTrue(property_exists($entity, 'keyArrayClass'));
        $this->assertIsArray($entity->keyArrayClass);
        $this->assertTrue($entity->keyArrayClass[0] instanceof EntityJsonToClass);
        $this->assertTrue($entity->keyArrayClass[1] instanceof EntityJsonToClass);
        $this->assertFalse($entity->keyArrayClass[0]->active);
        $this->assertTrue($entity->keyArrayClass[1]->active);
        $this->assertEquals(20, $entity->keyArrayClass[0]->id);
        $this->assertEquals(21, $entity->keyArrayClass[1]->id);

        $this->assertTrue(property_exists($entity, 'keyWithMutator'));
        $this->assertIsBool($entity->keyWithMutator);
        $this->assertTrue($entity->keyWithMutator);
    }

    /**
     * Teste o mapeamento de um json para class
     * 
     * @return void
     * @test
     */
    public function json_to_class()
    {
        $entity = new class extends BaseJsonToClass implements JsonToClass {
            public int $id;
            public array $keyArray;
            public bool $keyBool;
            public ?int $keyNull;
            public float $keyFloat;
            public EntityJsonToClass $keyClass;
            public array $keyArrayClass;

            protected array $cast = [
                'keyClass' => EntityJsonToClass::class,
                'keyArrayClass' => EntityJsonToClass::class
            ];
        };

        $entity->make('{"id":1,"keyFalse":"teste","keyArray":{"id":10,"name":"name"},"keyBool":false,"keyNull":null,"keyFloat":4.2,"keyClass":{"id":1,"active":true},"keyArrayClass":[{"id":20,"active":false},{"id":21,"active":true}]}');

        $this->assertTrue(property_exists($entity, 'id'));
        $this->assertIsInt($entity->id);
        $this->assertEquals(1, $entity->id);

        $this->assertFalse(property_exists($entity, 'keyFalse'));

        $this->assertTrue(property_exists($entity, 'keyArray'));
        $this->assertIsArray($entity->keyArray);
        $this->assertEquals([
            'id' => 10,
            'name' => 'name'
        ], $entity->keyArray);

        $this->assertTrue(property_exists($entity, 'keyBool'));
        $this->assertIsBool($entity->keyBool);
        $this->assertEquals(false, $entity->keyBool);

        $this->assertTrue(property_exists($entity, 'keyFloat'));
        $this->assertIsFloat($entity->keyFloat);
        $this->assertEquals(4.2, $entity->keyFloat);

        $this->assertTrue(property_exists($entity, 'keyNull'));
        $this->assertEquals(null, $entity->keyNull);

        $this->assertTrue(property_exists($entity, 'keyClass'));
        $this->assertTrue($entity->keyClass instanceof EntityJsonToClass);
        $this->assertTrue(property_exists($entity->keyClass, 'active'));
        $this->assertTrue(property_exists($entity->keyClass, 'id'));
        $this->assertEquals(true, $entity->keyClass->active);
        $this->assertEquals(1, $entity->keyClass->id);

        $this->assertTrue(property_exists($entity, 'keyArrayClass'));
        $this->assertIsArray($entity->keyArrayClass);
        $this->assertTrue($entity->keyArrayClass[0] instanceof EntityJsonToClass);
        $this->assertTrue($entity->keyArrayClass[1] instanceof EntityJsonToClass);
        $this->assertFalse($entity->keyArrayClass[0]->active);
        $this->assertTrue($entity->keyArrayClass[1]->active);
        $this->assertEquals(20, $entity->keyArrayClass[0]->id);
        $this->assertEquals(21, $entity->keyArrayClass[1]->id);
    }
}
