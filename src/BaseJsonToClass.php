<?php

declare(strict_types=1);

namespace ElielFC\JsonToClass;

use DomainException;
use ElielFC\JsonToClass\Contracts\JsonToClass;
use InvalidArgumentException;
use ReflectionProperty;

abstract class BaseJsonToClass
{
    protected array $cast = [];

    public function make(string|array $json)
    {
        if (!is_array($json)) {
            $json = $this->jsonParseArray($json);
        }

        $this->makeToArray($json);

        return $this;
    }

    private function jsonParseArray(string $json): array
    {
        $json = json_decode($json, true);

        if (!is_array($json)) throw new InvalidArgumentException();

        return $json;
    }

    private function makeToArray(array $values): void
    {
        foreach ($values as $key => $value) {
            if (!$this->hasProperty($key)) {
                continue;
            }

            if ($this->isCastable($key)) {
                $this->makeCastable($key, $value);
                continue;
            }

            $this->setProperty($key, $value);
        }
    }

    private function hasProperty(string $property): bool
    {
        return property_exists($this, $property);
    }

    private function isCastable(string $key): bool
    {
        return array_key_exists($key, $this->cast)
            && array_key_exists(
                JsonToClass::class,
                class_implements($this->cast[$key])
            );
    }

    private function makeCastable(string $property, mixed $values): void
    {
        if ($this->getPropertyType($property) === $this->cast[$property]) {
            $entity = new $this->cast[$property]();
            $this->$property = $entity->make($values);
            return;
        }

        if ($this->getPropertyType($property) === "array" && is_array($values)) {
            foreach ($values as $key => $value) {
                $entity = new $this->cast[$property]();
                $this->$property[] = $entity->make($value);
            }
            return;
        }

        throw new DomainException();
    }

    private function getPropertyType(string $key): string
    {
        return (string)(new ReflectionProperty($this, $key))->getType();
    }

    private function setProperty(string $property, mixed $value): void
    {
        if (method_exists($this, sprintf("set%sAttribute", ucfirst($property)))) {
            $mutatorName = sprintf("set%sAttribute", ucfirst($property));
            $this->$mutatorName($value);
            return;
        }

        $this->$property = $value;
    }
}
