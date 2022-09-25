<?php

namespace ElielFC\ToClass\Test\Unit;

use ElielFC\ToClass\{
    Contract\ToClass,
    BaseToClass
};
use PHPUnit\Framework\TestCase;

class ToClassTest extends TestCase
{
    /**
     * Teste o mapeamento de um array para class
     *
     * @return void
     * @test
     */
    public function array_to_class()
    {
        $entity = new class extends BaseToClass implements ToClass {
            public int $id;
            public array $keyArray;
            public bool $keyBool;
            public ?int $keyNull;
            public float $keyFloat;
            public EntityToClass $keyClass;
            public array $keyArrayClass;
            public bool $keyWithMutator;

            protected array $cast = [
                'keyClass' => EntityToClass::class,
                'keyArrayClass' => EntityToClass::class
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
        $this->assertTrue($entity->keyClass instanceof EntityToClass);
        $this->assertTrue(property_exists($entity->keyClass, 'active'));
        $this->assertTrue(property_exists($entity->keyClass, 'id'));
        $this->assertEquals(true, $entity->keyClass->active);
        $this->assertEquals(1, $entity->keyClass->id);

        $this->assertTrue(property_exists($entity, 'keyArrayClass'));
        $this->assertIsArray($entity->keyArrayClass);
        $this->assertTrue($entity->keyArrayClass[0] instanceof EntityToClass);
        $this->assertTrue($entity->keyArrayClass[1] instanceof EntityToClass);
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
        $entity = new class extends BaseToClass implements ToClass {
            public int $id;
            public array $keyArray;
            public bool $keyBool;
            public ?int $keyNull;
            public float $keyFloat;
            public EntityToClass $keyClass;
            public array $keyArrayClass;

            protected array $cast = [
                'keyClass' => EntityToClass::class,
                'keyArrayClass' => EntityToClass::class
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
        $this->assertTrue($entity->keyClass instanceof EntityToClass);
        $this->assertTrue(property_exists($entity->keyClass, 'active'));
        $this->assertTrue(property_exists($entity->keyClass, 'id'));
        $this->assertEquals(true, $entity->keyClass->active);
        $this->assertEquals(1, $entity->keyClass->id);

        $this->assertTrue(property_exists($entity, 'keyArrayClass'));
        $this->assertIsArray($entity->keyArrayClass);
        $this->assertTrue($entity->keyArrayClass[0] instanceof EntityToClass);
        $this->assertTrue($entity->keyArrayClass[1] instanceof EntityToClass);
        $this->assertFalse($entity->keyArrayClass[0]->active);
        $this->assertTrue($entity->keyArrayClass[1]->active);
        $this->assertEquals(20, $entity->keyArrayClass[0]->id);
        $this->assertEquals(21, $entity->keyArrayClass[1]->id);
    }

    /** 
     * Teste o mapeamento de um json invalido 
     * 
     * @return void
     * @test
     */
    public function it_json_invalid_fail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $json = "{'id': 1, 'active': 'TESTE'}";
        $entity = new EntityToClass();
        $entity->make($json);
    }

    /**
     * Teste a atribuição de valores em propriedades de tipos diferentes
     * 
     * @return void
     * @test
     */
    public function it_set_property_with_type_many_different_fail()
    {
        $this->expectException(\DomainException::class);

        $entity = new class extends BaseToClass implements ToClass {
            public int $id;
            public string $keyClass;

            protected array $cast = [
                'keyClass' => EntityToClass::class
            ];
        };

        $entity->make('{"id":1,"keyClass":{"id":1,"active":true}}');
    }

    /**
     * Teste a atribuição de valores em propriedades sem tipos definido
     * 
     * @return void
     * @test
     */
    public function it_set_property_without_type_fail()
    {
        $this->expectException(\DomainException::class);

        $entity = new class extends BaseToClass implements ToClass {
            public int $id;
            public $keyClass;

            protected array $cast = [
                'keyClass' => EntityToClass::class
            ];
        };

        $entity->make('{"id":1,"keyClass":{"id":1,"active":true}}');
    }
}
