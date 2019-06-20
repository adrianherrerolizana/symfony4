<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContieneDni extends Constraint
{
     public $message = '"%string%"';
}
