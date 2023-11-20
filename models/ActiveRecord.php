<?php
namespace Model;
 
class ActiveRecord {
    // BBDD
    protected static $db; // es la misma conexion, no se instancia más de una vez
    protected static $columnasDB = [];
    protected static $tabla = '';
 
    // Validaciones - protected porque solo se usará en la clase. static porque no se va a instanciar
    protected static $errores = [];
 
    public static function setDB($database) { // Funciona static porque lo es la variable
        self::$db = $database;
    }
 
    public function guardar() {
        if (!is_null($this->id)) { // Revisa que exista y que tenga un valor
            $this->actualizar();
        } else {
            $this->crear();
        }
    }
 
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        // Insertar en la BD
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join( ', ', array_keys($atributos) );
        $query .= " ) VALUES (' ";
        $query .= join( "', '", array_values($atributos) );
        $query .= " ') ";
        
        $resultado = self::$db->query($query); // self:: var es estatica. 
 
        // Mensaje de exito o error
        if ($resultado) {
            // Redireccionamos al usuario
            header('Location: /admin?resultado=1');
        }
 
        return $resultado;
    }
 
    public function actualizar() {
        // Sanitizar los datos
        
        $atributos = $this->sanitizarAtributos();
        $valores = [];
 
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";  
        }   
        
        // Insertar en la BD
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join( ', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id). "'";
        $query .= " LIMIT 1"; // Es recomendable poner limite
        
        $resultado = self::$db->query($query); // self:: var es estatica. 
 
        // Mensaje de exito o error
        if ($resultado) {
            // Redireccionamos al usuario
            header('Location: /admin?resultado=2');
        }
    }
 
    // Eliminar un registro
    public function eliminar() {
        // Eliminar el registro
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id). " LIMIT 1";
        $resultado = self::$db->query($query);
        
        if ($resultado) {
            $this->borrarImagen();
            // Redireccionamos al usuario
            header('Location: /admin?resultado=3');
        }
    }
 
    // Identifica y une los resultados DB
    public function atributos() {
        $atributos = [];
        foreach ( static::$columnasDB as $columna ) {
            if ($columna == 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }
    
    // Sanitizar
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
 
        foreach( $atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
 
        return $sanitizado;
    }
 
    // Subida archivos
    public function setImagen($imagen) {
        // Elimina la imagen previa
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }
        // Asignar el atributo imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }
 
    public function borrarImagen() {
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }
 
    // Errores
    public static function getErrores() {
        return static::$errores; 
    }
 
    public function validar() {
        static::$errores = [];
        return static::$errores; 
    }
 
    // Lista todas los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);  
        return $resultado;
    }
 
    // Obtiene determinado nº de registros
    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);  
        return $resultado;
    }
 
    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
        $resultado = self::consultarSQL($query);  
        return array_shift($resultado); // array_shift = retorna el primer elemento de un array
    }
 
 
    public static function consultarSQL($query) {
        // Consultar BBDD
        $resultado = self::$db->query($query);
 
        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        
        // Liberar la memoria
        $resultado->free();
 
        // Retornar los resultados
        return $array;
    }
 
    protected static function crearObjeto($registro) {
        $objeto = new static;
        foreach ( $registro as $key => $value ) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        
        return $objeto;
    }
 
    // Sincroniza el objeto en memoria con los datos actualizados por el usuario
    public function sincronizar($args = []) {
        foreach ( $args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}