<?php

include "forms.php";
#include "form-es.php";

#for test only
class MyForm extends Form {
    function __construct($data=NULL, $auto_id=False) {
        $this->nick = new CharField('Nick', '', True);
        $this->nombre = new CharField('Nombre', '', True); #campo requerido
        $this->apellido = new CharField('Apellido');
        $this->sexo = new SelectionField('Sexo', array('M'=>'Masculino', 'F'=>'Femenino'));

        #este metodo debe ser el ultimo en ser llamado en la clase heredada
        parent::__construct($data, $auto_id);
    }


    #metodos personalizado de validacion de campo
    function clean_nombre() {
        $pos = strpos($this->nombre->value, "hi");
        if ($pos === False) {
            $this->nombre->add_exception('Error: El campo no contiene la palabra  hi');
            return False;
        }
        else { #valid
            return True;
        }
    }

    function clean_apellido() {
        if (!strcmp($this->nombre, $this->apellido)) {
            return True;
        }
        else {
            $this->apellido->add_exception('Error: El campo no coincide con nombres');
            return False;
        }
    }

}


#$field = new FormField();
#echo $field;

#$tf =  new TextField('nombre','Su nombre');

#echo "<br />";

#echo $tf->label();
#echo $tf;

/*
$e = new FormFieldErrors();
$e->add_msj("Error: Nombre Usuario Invalido");
$e->add_msj("Error: Apellido Usuario Invalido");

echo $e;
*/

echo "<br /> <br />";

$form = new MyForm(True);
$form->nombre->insert_attr('class', 'miclases');
$d = array('nombre' => 'Ricardo', 'apellido'=>'Quiroga');
$form->set_data($d);
$form->is_valid();
#echo $form;

echo $form->as_table();

#$form->nombre->errors->add_msj("test");

#echo $form->nombre->errors;
echo $form->errors();



#echo form.field.errors
?>
