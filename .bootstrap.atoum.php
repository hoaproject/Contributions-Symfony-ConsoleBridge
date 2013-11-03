<?php

namespace {
    require __DIR__ . '/vendor/autoload.php';
}

namespace mock {
    use mageekguy\atoum\mock;

    class staticClass implements mock\aggregator {
        protected static $instance;

        protected $controller;

        public static function getInstance()
        {
            if (null === static::$instance) {
                static::$instance = new static();

                $controller = new mock\controller();
                static::$instance->setMockController($controller->notControlNextNewMock());
            }

            return static::$instance;
        }

        public static function __callStatic($method, $arguments)
        {
            return call_user_func_array(array(static::getInstance()->controller, $method), $arguments);
        }

        public function getMockController()
        {
            return $this->controller;
        }

        public function setMockController(mock\controller $mockController)
        {
            $this->controller = $mockController;

            return $this;
        }

        public function resetMockController()
        {
            $this->controller->reset();

            return $this;
        }

        public static function getMockedMethods()
        {
            return array();
        }
    }
}
