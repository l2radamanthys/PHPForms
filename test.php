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
    function clean_nombre() 
    {
        $pos = strpos($this->nombre->value, "hi");
        if ($pos === False) {
            $this->nombre->add_exception('Error: El campo "nombre" no contiene la palabra  hi');
            return False;
        }
        else { #valid
            return True;
        }
    }

    function clean_apellido() 
    {
        if (strcmp($this->nombre->value, $this->apellido->value)) 
        {
            return True;
        }
        else 
        {
            $this->apellido->add_exception('Error: El campo "apellido" no coincide con nombres');
            return False;
        }
    }

}


$form = new MyForm(True);
$form->get_data();
#$form->nombre->insert_attr('class', 'miclases');
#$d = array('nombre' => 'Ricardo', 'apellido'=>'Quiroga');
#$form->set_data($d);
#echo $form;

echo '<form action="" method="POST">';
echo "<table>";
echo $form->as_table();
echo '<tr><td colspan="2"><input type="submit"></td></tr>';
echo "</table></form>";

if (!$form->is_valid())
{
    echo $form->errors();
}
?>
