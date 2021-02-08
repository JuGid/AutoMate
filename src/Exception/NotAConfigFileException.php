<?php

namespace Automate\Exception;

use Exception;

class NotAConfigFileException extends Exception {

  public function __construct($code = 2, Exception $previous = null) {
    $this->message = "The filepath of your own config file doesn\'t exist or is not a Yaml file.\n";
    parent::__construct($this->message, $code, $previous);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
