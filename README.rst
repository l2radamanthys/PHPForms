PHPForms
========
PHPForms es una libreria que permite crear formularios web, rapidamente, 
esta basado en la idea de *Django.Forms*

Autor
-----
    - **Nombre/Nick:** Ricardo Daniel Quiroga (L2Radamanthys)  
    - **Email:** ricardoquiroga.dev@gmail.com / l2radamanthys@gmail.com
    - **Web:**  http://www.l2radamanthys.com.ar o http://www.co2soft.com.ar


Requerimientos
--------------
   - PHP >= 5.3


Campos Soportados
-----------------
    - CharField (campo de texto simple TextImput)
    - PasswordField (campo para ingreso de contraseña PasswordImput)
    - TextField (Area de texto TextArea)
    - SelectionField


Uso
---
  Para crear un formulario se debe extender la clase Form y dentro de ella 
  crear los correspondientes elementos que contendra el formulario

    ```
    class MyForm extends Form {
        function __construct($data=NULL, $auto_id=False) {
            $this->nick = new CharField('Nick', '', True);
            $this->nombre = new CharField('Nombre', '', True); #campo requerido
            $this->apellido = new CharField('Apellido');
            $this->sexo = new SelectionField('Sexo', array('M'=>'Masculino', 'F'=>'Femenino'));
            
            #este metodo debe ser el ultimo en ser llamado en la clase heredada
            parent::__construct($data, $auto_id);
        }
    }
    ```


