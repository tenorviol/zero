<?php

require_once 'lib/zero/core/Container.php';

use zero\core\Container;

class ContainerTest extends PHPUnit_Framework_TestCase {

  public function testPropertyReturnsPremadeAndManufacturedData() {
    $container = new TestContainer();

    // simple set and retrieve
    $container->foo = 'bar';
    $this->assertEquals('bar', $container->foo);

    // implicitly call createX method
    $this->assertFalse(isset($container->test));
    $this->assertEquals('Hello world!', $container->test);
    $this->assertTrue(isset($container->test));
  }

  /**
   * @expectedException BadMethodCallException
   */
  public function testPropertyWithoutFactoryThrowsException() {
    $container = new Container();
    $container->john;
  }

  /**
   * @expectedException BadMethodCallException
   */
  public function testUndefinedMethodThrowsException() {
    $container = new Container();
    $container->jacob();
  }
}

class TestContainer extends Container {
  public function createTest() {
    return 'Hello world!';
  }
}
