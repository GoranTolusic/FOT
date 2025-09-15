<?php

namespace App\Dto;

abstract class BaseAbstractDto
{
    final public function toEntity(object $entity, array $excluded = [])
    {
        $dtoValues = get_object_vars($this);
        $entityRef = new \ReflectionClass($entity);

        foreach ($dtoValues as $name => $value) {
            if ($entityRef->hasProperty($name) && !in_array($name, $excluded, true)) {
                $entityProp = $entityRef->getProperty($name);
                if (!$entityProp->isReadOnly()) {
                    $entityProp->setAccessible(true);
                    $entityProp->setValue($entity, $value);
                }
            }
        }

        return $entity;
    }
}
