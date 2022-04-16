<?php

namespace App\CAS;

use EcPhp\CasLib\Contract\Configuration\PropertiesInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ConfigurableSymfony implements PropertiesInterface
{
    private PropertiesInterface $properties;

    private RequestStack $requestStack;

    public function __construct(PropertiesInterface $properties, RequestStack $requestStack) {
        $this->properties = $properties;
        $this->requestStack = $requestStack;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool {
        return array_key_exists($offset, $this->all());
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset) {
        return $this->all()[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value) {
        $this->properties->offsetSet($offset, $value);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset) {
        $this->properties->offsetUnset($offset);
    }

    public function all(): array {
        return array_merge(
            $this->properties->all(),
            $this
                ->requestStack
                ->getCurrentRequest()
                ->getSession()
                ->get('configuration', [])
        );
    }
}
