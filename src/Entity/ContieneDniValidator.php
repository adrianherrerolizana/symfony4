<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContieneDniValidator extends ConstraintValidator
{
     public function validate($value, Constraint $constraint)
     {
          if (!preg_match("/\d{1,8}[a-z]/i", $value, $matches)) {
               $this->context->addViolation($constraint->message, array('%string%' => 'La cadena '.$value.' contiene un caracter ilegal: solo puede contener letras y números'));
          }

          // Comprobar que la letra cumple con el algoritmo
	      $numero = substr($value, 0, -1);
          $letra  = strtoupper(substr($value, -1));
          if ($letra != substr("TRWAGMYFPDXBNJZSQVHLCKE", strtr($numero, "XYZ", "012")%23, 1))
                $this->context->addViolation($constraint->message, array('%string%' => 'La letra no coincide con el número del DNI. Comprueba que has escrito bien tanto el número como la letra'));
      }
}