<?php

namespace Automate\Exception;

use Exception;

class NotImplementedException extends Exception {

  public function __construct($message, $code = 3, Exception $previous = null) {
    $this->message = $message;
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
