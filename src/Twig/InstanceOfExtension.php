<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;

use Twig\TwigTest;

class InstanceOfExtension extends AbstractExtension {

    public function getTests(): array {
        return array(
            new TwigTest('instanceof', array($this, 'isInstanceOf')),
        );
    }

    public function isInstanceOf($var, $instance) {
        $reflexionClass = new \ReflectionClass($instance);
        return $reflexionClass->isInstance($var);
    }
}