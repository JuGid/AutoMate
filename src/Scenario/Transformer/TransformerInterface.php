<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Validator\ValidatorInterface;

interface TransformerInterface {
    public function transform($driver, array $step, ValidatorInterface $validator);
}