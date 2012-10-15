<?php

namespace zero\core;

class Logger_OutHtml extends Logger_OutText {
  public function log($priority, $message) {
    echo
      '<pre class="log ', self::$priorityMap[$priority] ,'">',
        date(self::$dateFormat), "\t", htmlentities($message),
      '</pre>';
  }
}
