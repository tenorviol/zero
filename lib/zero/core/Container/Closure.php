<?php

namespace zero\core;

class Container_Closure extends Container {
  private $_closure;

  public function __construct($closure) {
    $this->_closure = $closure;
  }

  public function getInstance($property) {
    return call_user_func($this->_closure, $property);
  }
}
