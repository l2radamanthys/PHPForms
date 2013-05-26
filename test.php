<?php

include "forms.php";
include "form-es.php

#for test only
class MyForm extends Form {
    var $nombre;
    var $apellido;

    function __construct() {
        #$this->nombre = new TextField('Nombre', 'Ricardo', array('style'=>'border: 1px solid #000;'));
        $this->nombre = new TextField('Nombre', 'Ricardo');
        $this->apellido = new TextField('Apellido', 'Quiroga');
    }

}



#$field = new FormField();
#echo $field;

#$tf =  new TextField('nombre','Su nombre');

#echo "<br />";

#echo $tf->label();
#echo $tf;


$e = new FormFieldErrors();
$e->add_msj("Error: Nombre Usuario Invalido");
$e->add_msj("Error: Apellido Usuario Invalido");

echo $e;

echo "<br /> <br />";

$form = new MyForm();
echo $form;

#echo form.field.errors
?>
