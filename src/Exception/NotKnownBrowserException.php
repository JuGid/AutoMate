<?php

namespace Automate\Exception;

use Exception;

class NotKnownBrowserException extends Exception {

  public function __construct($code = 1, Exception $previous = null) {
    $this->message = "The browser is unknown. Browser supported actually : chrome.\n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
