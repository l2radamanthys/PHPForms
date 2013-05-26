<?php



class FormFieldErrors implements Iterator {
    var $position;
    var $errors;

    function __construct() {
        $this->errors = array();
        $position = 0;
    }


    #conjunto de metodos abstratos para funcionar como iterador
    function rewind() {
        $this->position = 0;
    }


    function current() {
        return $this->array[$this->position];
    }


    function key() {
        return $this->position;
    }


    function next() {
        $this->position += 1;
    }


    function valid() {
        return isset($this->array[$this->position]);
    }


    function add_msj($text) {
        $this->errors[] = $text; 
    }


    function __toString() {
        $str = "<ul class=\"form-error\">\n";
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
 */ 
class FormField {
    var $label;
    var $name;
    var $value;
    var $other_attr;


    function __construct($label='', $value='', $attr=NULL) {
        $this->label = $label;
        $this->value = $value;
        $this->name = "";
        $this->other_attr = $attr;
    }


    /*
     * agregar otros atributos especificos del elemento
     */ 
    function set_attr($attr=array()) {
        $this->other_attr = $attr;
    }
    

    function concat_attr() {
        $str = "";
        if ($this->other_attr != NULL) {
            foreach($this->other_attr as $field => $value) {
                $str .= $field.'="'.$value.'" ';
            }
        }
        return $str;
    }


    function label() {
        return "<label>".$this->label.": </label>";
    }
    

    function __toString() {
        return 'Invalid Field';
    }

}

/*
 * Objecto Campo de Texto
 */ 
class TextField extends FormField {
    
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
 * Clase Formulario base
 *
 * Para Crear un formulario personalizado se debe crear una subclase e incluirle
 * los correspondientes atributos
 * 
 */ 
class Form {
    var $errors;
    function __construct() {
    }


    function set_data($dict) {
        
    }

    
    function is_valid() {
    }


    function to_table() {
    }

    function __toString() {
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
