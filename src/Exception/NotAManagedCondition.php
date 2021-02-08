<?php

namespace Automate\Exception;

use Exception;

class NotAManagedCondition extends Exception {

  public function __construct(string $type, $code = 3, Exception $previous = null) {
    $this->message = "This condition [" . $type . "] is not managed. \n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
