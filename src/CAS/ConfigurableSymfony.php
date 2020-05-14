<?php

namespace App\CAS;

use EcPhp\CasLib\Configuration\PropertiesInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConfigurableSymfony implements PropertiesInterface
{
    /**
     * @var \EcPhp\CasLib\Configuration\PropertiesInterface
     */
    private $properties;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    public function __construct(PropertiesInterface $properties, RequestStack $requestStack) {
        $this->properties = $properties;
        $this->requestStack = $requestStack;
    }

    public function offsetExists($offset) {
        return $this->properties->offsetExists($offset);
    }

    public function offsetGet($offset) {
        return $this->properties->offsetGet($offset);
    }

    public function offsetSet($offset, $value) {
        $this->properties->offsetSet($offset, $value);
    }

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
