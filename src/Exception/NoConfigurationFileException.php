<?php

namespace Automate\Exception;

use Exception;

class NoConfigurationFileException extends Exception {

  public function __construct($code = 3, Exception $previous = null) {
    $this->message = "Please attach a configuration to the DriverManager\n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
