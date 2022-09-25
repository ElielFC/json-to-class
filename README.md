# To Class
Converte um json ou um array para uma class personalizada.

[![Actions Status](https://github.com/ElielFC/to-class/workflows/Tests/badge.svg)](https://github.com/ElielFC/to-class/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/elielfc/to-class.svg?style=flat-square)](https://packagist.org/packages/elielfc/to-class)
[![codecov](https://codecov.io/github/ElielFC/to-class/branch/main/graph/badge.svg?token=K89ZHEN8AK)](https://codecov.io/github/ElielFC/to-class)

# Instalação

```shell
composer require elielfc/to-class
```
# Instruções de uso

## Basico
Crie uma classe que implemente `\ElielFC\ToClass\Contract\ToClass` e estenda `\ElielFC\ToClass\BaseToClass`.  
Defina suas propriedade e seus tipos.
```php
<?php

/*...*/

use ElielFC\ToClass\{
    Contract\ToClass,
    BaseToClass
};

class EntityToClass extends BaseToClass implements ToClass
{
    public int $id;
    public bool $active;
}
```
Crie uma instancia dessa classe e chame o método `make`
```php
$entity = new EntityToClass();
$entity->make(["id" => 10, "active" => true, "name" => "Eliel F Canivarolli", /*...*/]);
```
Nesse exemplo as propriedades da sua instancia de entity sera populada com as informações de `id`, `active` e a informação de `nome` não sera salvo, pois não a uma propriedade nome na classe.  
O `make` também aceita um json como argumento.
```php
$entity->make('{"id":"10", "active":"true", "name":"Eliel F Canivarolli"}');
```
Se durante o bind você precise fazer algum tratamento na informação, base adicionar uma função na sua classe que tenha o nome no seguinte formato `set` + nome da propriedade + `Attribute`
```php
/*...*/

public bool $active;

public function setActiveAttribute(mixed $value): void
{
    $this->active = $value === "VERDADEIRO";
}

/* Ex: Atribuição em um array */

public array $phone:

public function setPhoneAttribute(mixed $value): void
{
    $this->phone[] = $value;
}

/*...*/
```

Caso uma das suas propriedade seja um objeto e também deseje mapear para uma classe personalizada, user a propriedade `$cast`.
```php
/*...*/

public NewEntityToClass $entity;

protected array $cast = [
    'entity' => NewEntityToClass::class,
];

/*...*/
```
Sua `NewEntityToClass` tem que estender `BaseToClass` e implementar `ToClass`

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.