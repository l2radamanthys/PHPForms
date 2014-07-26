<?php

/*
 * PHPForms
 * --------
 *
 * PHPForms esta basado en Django.Forms aunque con algunos cambios y agregados,
 * para permitir que el usuario personalise sus formularios ademas de ayudarlo
 * a crearlo y validar datos.
 *
 * Autor: Ricardo Daniel Quiroga - l2Radamanthys
 * Email: l2radamanthys@gmail.com / ricardoquiroga.dev@gmail.com
 * Web: http://www.co2soft.com.ar
 * Licencia: GPL3
 * Estado: Alfa (Prueba)
 * Ultima Modificacion: 22/7/2013
 *
 */

include "condition.php";

//include con los mensajes segun el lenguaje
include "form-lang-es.php";


/*
 * Clase para listar los errores de un campo
 *
 */
class FormFieldErrors implements Iterator {
    var $position;
    var $errors;

    function __construct() 
    {
        $this->errors = array();
        $position = 0;
    }


    /*
     * Conjunto de metodos abstratos para funcionar como iterador
     */
    function rewind() 
    {
        $this->position = 0;
    }


    function current() 
    {
        return $this->array[$this->position];
    }


    function key() 
    {
        return $this->position;
    }


    function next() 
    {
        $this->position += 1;
    }


    function valid() 
    {
        return isset($this->array[$this->position]);
    }


    /*
     * Agrega un mensaje de error en formato texto, a la lista de Errores
     *
     * @param string $text
     */
    function add_msj($text) {
        $this->errors[] = $text;
    }


    /*
     * Consulta si no hay Errores devolviendo un valor TRUE, y en caso que los
     * ubiese devolvera un valor False
     *
     * @return boolean
     *
     */
    function not_errors() {
        if (count($this->errors) == 0) {
            return True;
        }
        else  {
            return False;
        }
    }

    /*
     * Por defecto Formateara la lista de Errores como una lista HTML, si se desea
     * personalizar el estilo de error defina una clase CSS3 .php-form-error
     */
    function __toString() {
        $str = "<ul class=\"php-form-error\">\n";
        foreach($this->errors as $error) {
            $str .= "\t<li>".$error."</li>\n";
        }
        $str .= "</ul>\n";
        return $str;
    }

}


/*
 * Campo Basico de formulario
 *
 * El nombre de campo en html sera el mismo que el nombre de la variable
 *
 */
class FormField {
    var $label;
    var $value;
    var $name; #nombre interno del atributo, el cual tomara el nombre de la variable
    var $required;
    var $other_attr;
    var $condition;
    var $errors;
    var $auto_id;

    /*
     * Constructor de la clase
     *
     * @param string $label etiqueta del campo
     * @param string $value valor por defecto del campo
     * @param boolean $required si el campo es obligatorio
     * @param array $attr atributos adicionales dentro del campo
     * @param array condition condiciones de evaluacion
     */
    function __construct($label='', $value='', $required=False, $attr=NULL, $condition=array('NOT_NULL'=>'NULL')) {
        $this->label = $label;
        $this->value = $value;
        //$this->name = "";
        if (is_array($attr)) {
            $this->other_attr = $attr;
        }
        else {
            $this->other_attr = array();
        }
        $this->required = $required;
        $this->condition = $condition;        
        $this->errors = new FormFieldErrors();
        $this->auto_id = False;
    }


    /*
     * Agregar otros atributos especificos del elemento
     *
     * @param array $attr
     */
    function set_attr($attr=array()) {
        $this->other_attr = $attr;
    }


    /*
     * Inserta un atributo particular del tipo $key="$value"
     *
     * @param string $key identificador del elemento
     * @param string $value valor asignado del elemento
     */
    function insert_attr($key, $value) {
        $this->other_attr[$key] = $value;
    }


    /*
     * evalua las validaciones genericas en caso que sea requerido validar
     * el campo del formulario
     *
     */
    function required_validate() {
        if ($this->required) {
            if (array_key_exists('NOT_NULL', $this->condition)) 
            {
                if ($this->value == '' || $this->value == NULL) {
                    $this->errors->add_msj(NOT_NULL_PRE.$this->name.NOT_NULL_POS);
                }
            }

            if (array_key_exists('IS_INT', $this->condition)) 
            {
                if (!field_is_int($this->value))
                {
                    $this->errors->add_msj(IS_INT_PRE.$this->name.IS_INT_POS);
                }
            }

        }
    }

    /*
     * Metodo para validar el campo especifico
     */
    #function validate() {
    #    //pass;
    #}


    function is_valid() {
        return $this->errors->not_errors();
    }


    /*
     * Metodo interno de la clase que permite concatener en un string todos los
     * atributos alternativos del campo
     *
     * @return string
     */
    function concat_attr() {
        $str = "";
        if ($this->other_attr != NULL) {
            foreach($this->other_attr as $field => $value) {
                $str .= $field.'="'.$value.'" ';
            }
        }
        return $str;
    }

    /*
     * Metodo que retorna la etiqueta del campo dentro de los tags <label>
     *
     * @return string
     */
    function label() {
        return "<label>".$this->label.": </label>";
    }


    /*
     * No esta definido para el campo base, este retornara un string con el
     * siguiente mensaje "Invalid Filed"
     */
    function __toString() {
        return 'Invalid Field';
    }

    /*
     * Agrega un mensaje de error personalizado al campo
     *
     * @param string $text texto con el mensaje de error
     */
    function add_exception($text) {
        $this->errors->add_msj($text);
    }
}


/*
 * Clase Campo de Ingreso de Texto basico
 */
class CharField extends FormField {
    function __toString() {
        if ($this->value != "") {
            return '<input type="text" name="'.$this->name.'" value="'.$this->value.'" '.$this->concat_attr().' />';
        }
        else {
            return '<input type="text" name="'.$this->name.'" '.$this->concat_attr().' />';
        }
    }
}


/*
 * Clase para elemento Contraseña
 */
class PasswordField extends FormField {
    function __toString() {
        if ($this->value != "") {
            return '<input type="password" name="'.$this->name.'" value="'.$this->value.'" '.$this->concat_attr().' />';
        }
        else {
            return '<input type="password" name="'.$this->name.'" '.$this->concat_attr().' />';
        }
    }
}


/*
 * Clase Para Definir Area de Texto
 */
class TextField extends FormField {
    function __toString() {
        return '<textarea name="'.$this->name.'" '.$this->concat_attr().' >'.$this->value.'</textarea>';
    }
}


/*
 * Clase Campo de Selecion o Combo Box
 */
class SelectionField extends FormField {
    var $options;


    /*
     * Constructor de la clase
     */
    function __construct($label='', $options=array(), $value='', $attr=NULL) {
        parent::__construct($label, $value, False, $attr);
        $this->options = $options;
    }


    function __toString() {
        $str = '<select name="'.$this->name.'" '.$this->concat_attr().' >'."\n";
        foreach($this->options as $key => $value) {
            $str .= "\t".'<option value="'.$key.'">'.$value."</option>\n";
        }
        $str .= "</select>\n";
        return $str;
    }
}


class CheckField extends FormField {
    //no implementado
}


class RadioField extends FormField {
    //no implementado
}



/*
 * Clase para definir un campo de ingreso de URL
 */
class URLField extends FormField {
    //no implementado
}



class EmailField extends FormField {
    //no implementado
}


class IntField extends FormField {
    //no implementado
}


class PhoneField extends FormField {
    //no implementado
}


class ColorField extends FormField {
    //no implementado
}

class DateField extends FormField {
    //no implementado
}

class TimeField extends FormField {
    //no implementado
}


class DateTimeField extends FormField {
    //no implementado
}





/*
 * Clase Formulario base
 *
 * Para Crear un formulario personalizado se debe crear una subclase al cual se
 * de definiran los correspondientes atributos
 *
 */
class Form {
    var $errors;
    function __construct($data=NULL, $auto_id=False) {
        foreach(get_object_vars($this) as $name => $obj) {
            if ($obj != NULL) {
                $obj->name = $name;
                if ($auto_id) {
                    $obj->insert_attr('id', $name);
                }
                #$obj->auto_id = $auto_id;

            }
        }
    }


    function set_data($dict) {
        foreach(get_object_vars($this) as $name => $obj) {
            if (isset($dict[$name])) {
                $obj->value = $dict[$name];
            }
        }
    }


    function add_exception($obj, $text) {
        $obj->add_exception($text);
    }


    function is_valid() {
        $valid = True;
        foreach(get_object_vars($this) as $name => $obj) {
            if ($obj != NULL) {
                #verficacion errores de los campos
                $obj->required_validate();
                #echo $obj->name;
            }
        }
        #busca metodos de validacion definido por usuario
        foreach(get_class_methods($this) as $fname) {
            $pos = strpos($fname, "clean_");
            if ($pos !== False) {
                $this->$fname();
            }
        }
    }


    /*
     * Formatea el formulario con una tabla
     *
     */
    function as_table() 
    {
        #$str = "<table> \n";
        $str = "";
        foreach(get_object_vars($this) as $name => $obj) {
            if ($obj != NULL) {
                $str .= "<tr>\n\t<td>".$obj->label()."</td>\n\t";
                $str .= "<td>".$obj."</td>\n</tr>\n";
            }
        }
        #$str .= "</table>\n";
        return $str;
    }


    /*
     * Formatea el Form
     */
    function as_p() 
    {
        #pass
    }


    /*
     * Retorna un String en formato HTML con el listado de errores segun  los
     * atributos del formulario.
     *
     * @return string
     */
    function errors() 
    {
        $str = "<ul class=\"form-error\">\n";
        foreach(get_object_vars($this) as $name => $obj) 
        {
            #busca metodos de validacion definido por usuario
            if ($obj != NULL) 
            {
               if (!$obj->errors->not_errors())
               {
                   $str .= "<li>".$name."\n";
                   $str .= $obj->errors;
                   $str .= "</li>\n";
               }
            }
        }
        $str .= "</ul>\n";
        return $str;
    }

    /*
     * No especificado
     */ 
    function errors_list() 
    {
        $errors = array();

    }


    /*
     * Formatea el objecto Crudo
     *
     * @return string //en formato html
     */
    function __toString() 
    {
        $str = "";
        foreach(get_object_vars($this) as $name => $obj) {
            if ($obj != NULL) {
                $obj->name = $name; #nombre de la variable que hace de nombre de objecto
                $str .= $obj->label();
                $str .= $obj;
                $str .= "<br />\n";
            }
        }
        return $str;
    }
}



?>
