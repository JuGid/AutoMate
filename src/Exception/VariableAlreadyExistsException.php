<?php

namespace Automate\Exception;

use Exception;

class VariableAlreadyExistsException extends Exception {

  public function __construct(string $variable_name, $code = 3, Exception $previous = null) {
    $this->message = "The variable '". $variable_name ."' already exists.\n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
