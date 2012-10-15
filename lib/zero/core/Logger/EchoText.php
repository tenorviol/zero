<?php

namespace zero\core;

class Logger_EchoText implements Logger {
  protected static $dateFormat = 'Y-m-d H:i:s';
  protected static $priorityMap = array(
    LOG_EMERG   => 'EMERG',   // system is unusable
    LOG_ALERT   => 'ALERT',   // action must be taken immediately
    LOG_CRIT    => 'CRIT',    // critical conditions
    LOG_ERR     => 'ERR',     // error conditions
    LOG_WARNING => 'WARNING', // warning conditions
    LOG_NOTICE  => 'NOTICE',  // normal, but significant, condition
    LOG_INFO    => 'INFO',    // informational message
    LOG_DEBUG   => 'DEBUG',   // debug-level message
  );

  public function log($priority, $message) {
    $this->write(date(self::$dateFormat) . "\t" . self::$priorityMap[$priority] . "\t" . $message);
  }

  protected function write($log) {
    echo $log, "\n";
  }
}
