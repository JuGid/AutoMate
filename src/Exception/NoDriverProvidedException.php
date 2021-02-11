<?php

namespace Automate\Exception;

use Exception;

class NoDriverProvidedException extends Exception {

  public function __construct($code = 3, Exception $previous = null) {
    $this->message = "The driver is not provided or null.\n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
