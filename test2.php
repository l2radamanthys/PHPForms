<?php

include "forms.php";



class MyForm extends Form {
    function __construct($data=NULL, $auto_id=False) {
        $this->username = new CharField('Nombre de Usuario');
        $this->pswd = new PasswordField('Contraseña', '', True);
        $this->pswd_re = new PasswordField('Repetir Contraseña', '', True);
        $this->nombre = new CharField('Nombre'); #campo requerido
        $this->apellido = new CharField('Apellido');

        parent::__construct($data, $auto_id);
    }



}
$f = new MyForm(NULL, False);

#si se hizo post
if (isset($_POST)) {
    $f->set_data($_POST);
}

?>

<form action="" method="post">
<table>
<?php echo $f->as_table(); ?>

<tr>
    <td colspan="2"><input type="submit" /></td>
</tr>
</table>
</form>

<?php
echo "<h3>Errors</h3>";
echo $f->errors();
/*
echo $f->username;
echo $f->nombre;
echo $f->apellido;
*/
?>


