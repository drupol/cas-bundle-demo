<?php

namespace App\CAS;

use EcPhp\CasLib\Configuration\PropertiesInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConfigurableSymfony implements PropertiesInterface
{
    /**
     * @var \EcPhp\CasLib\Configuration\PropertiesInterface
     */
    private $config;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    public function __construct(PropertiesInterface $config, RequestStack $requestStack) {
        $this->config = $config;
        $this->requestStack = $requestStack;
    }

    public function offsetExists($offset) {
        return $this->config->offsetExists($offset);
    }

    public function offsetGet($offset) {
        return $this->config->offsetGet($offset);
    }

    public function offsetSet($offset, $value) {
        $this->config->offsetSet($offset, $value);
    }

    public function offsetUnset($offset) {
        $this->config->offsetUnset($offset);
    }

    public function all(): array {
        return array_merge(
            $this->config->all(),
            $this
                ->requestStack
                ->getCurrentRequest()
                ->getSession()
                ->get('configuration', [])
        );
    }
}
