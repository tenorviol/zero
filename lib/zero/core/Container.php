<?php
/**
 * zero library
 * http://github.com/tenorviol/zero
 *
 * Copyright (c) 2010-2012 Christopher Johnson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace zero\core;

use ArrayAccess;
use BadMethodCallException;

/**
 * A very simple creator container.
 *
 * PROBLEM:
 *
 * There are many objects that you need exactly one of.
 * E.g.: database and memcache connections, table gateways, etc.
 * Each object requires a distinct instantiation algorthm,
 * often using one or more related dependencies,
 *
 * SOLUTION:
 *
 * An object of creation algorithms that also maintains singletons.
 *
 *  class MySite extends Container {
 *    public $configPath;
 *
 *    public function createConfig() {
 *      return parse_ini_file($this->configPath);
 *    }
 *
 *    public function createPdo() {
 *      return new PDO($this->config->pdo_dsn, $this->config->pdo_user, $this->config->pdo_pass);
 *    }
 *
 *    public function createTable() {
 *      return new TableGatewayContainer($this->pdo);
 *    }
 *  }
 *
 *  $site = new MySite();
 *  $site->configPath = __DIR__.'/config/site.ini';
 *  $statement = $site->pdo->prepare('SELECT count(*) FROM users');
 *  ...
 *
 *
 * When all objects require identical factory method,
 * a container can override the getInstance method.
 *
 *  class TableGatewayContainer extends Zero_Container {
 *    private $pdo;
 *
 *    public function __construct(PDO $pdo) {
 *      $this->pdo = $pdo;
 *    }
 *
 *    public function getInstance($table) {
 *      return new TableGateway($this->pdo, $table);
 *    }
 *  }
 *
 *  echo $site->table->users->count();
 */
class Container implements ArrayAccess {

  /**
   * If a property doesn't exist yet, create and store it.
   *
   * @param string $property
   * @return mixed
   */
  public function __get($property) {
    return $this->getInstance($property);
  }

  /**
   * Calling undefined methods throws,
   * rather than erroring out of process.
   *
   * @param string $method
   * @param array $arguments
   * @throws NotFoundException
   */
  public function __call($method, $arguments) {
    throw new BadMethodCallException(get_class($this)."::$method");
  }

  /**
   * Returns a new instance determined by the name.
   * Subclasses should either override this method to return an
   * appropriate instance of the name, or add createX methods,
   * which will be called directly from this function.
   *
   * @param string $property
   */
  public function getInstance($property) {
    $lcproperty = strtolower($property);
    if (!isset($this->$lcproperty)) {
      $method = 'create'.ucfirst($property);
      $this->$lcproperty = $this->$method();
    }
    return $this->$lcproperty;
  }

  public function offsetExists($offset) {
    return isset($this->$offset);
  }

  public function offsetGet($offset) {
    return $this->$offset;
  }

  public function offsetSet($offset, $value) {
    $this->$offset = $value;
  }

  public function offsetUnset($offset) {
    unset($this->$offset);
  }
}
