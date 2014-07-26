<?php

/*
 * Funciones Validadoras de Condiciones
 *
 */

function field_in_range_str($str, $min, $max) {
    $len = lenght($str);
    if ($len < $min OR $len > $max) {
        return False;
    }
    else {
        return True;
    }
}


function field_is_int($value)
{
    if (is_numeric($value))
    {
        return True;
    }
    else 
    {
        return False;
    }
}


?>
