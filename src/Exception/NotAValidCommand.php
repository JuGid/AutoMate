<?php

namespace Automate\Exception;

use Exception;

class NotAValidCommand extends Exception {

  public function __construct(string $command, $code = 3, Exception $previous = null) {
    $this->message = "The command [" . $command . "] is not valid. Can see the docs to help.\n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
