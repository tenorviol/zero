<?php

require_once 'lib/zero/core/Logger.php';
require_once 'lib/zero/core/Logger/EchoText.php';

use zero\core\Logger_EchoText;

class Logger_EchoTextTest extends PHPUnit_Framework_TestCase {
  public function test() {
    $logger = new Logger_EchoText();

    ob_start();
    $logger->log(LOG_EMERG, 'foo');
    $output = ob_get_contents();
    ob_end_clean();

    list($date, $priority, $message) = explode("\t", trim($output));
    $this->assertLessThanOrEqual(1, time() - strtotime($date));
    $this->assertEquals('EMERG', $priority);
    $this->assertEquals('foo', $message);
  }
}
