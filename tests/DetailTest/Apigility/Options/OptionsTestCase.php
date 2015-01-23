<?php

namespace DetailTest\Apigility\Options;

use PHPUnit_Framework_TestCase as TestCase;

abstract class OptionsTestCase extends TestCase
{
    /**
     * @param string $class
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getOptions($class, array $methods)
    {
        $mockedMethods = array_diff($this->getMethods($class), $methods);

        return $this->getMock($class, $mockedMethods);
    }

    /**
     * Helper to get all public and abstract methods of a class.
     *
     * This includes methods of parent classes.
     *
     * @param string $class
     * @param bool $autoload
     * @return array
     */
    protected function getMethods($class, $autoload = true)
    {
        $methods = array();

        if (class_exists($class, $autoload) || interface_exists($class, $autoload)) {
            $reflector = new \ReflectionClass($class);

            foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_ABSTRACT) as $method) {
                $methods[] = $method->getName();
            }
        }

        return $methods;
    }
}
