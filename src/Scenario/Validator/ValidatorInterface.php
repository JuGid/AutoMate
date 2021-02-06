<?php 

namespace Automate\Scenario\Validator;

interface ValidatorInterface {
    public function validate(array $step);
}