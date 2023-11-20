<?php 

namespace Model;


class Propiedad extends ActiveRecord {
   
   protected static $tabla = 'propiedades';
   protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedores_id'];


   
   public $id;
   public $titulo;
   public $precio;
   public $imagen;
   public $descripcion;
   public $habitaciones;
   public $wc;
   public $estacionamiento;
   public $creado;
   public $vendedores_id;


    
   public function __construct($args = [])
   {
       $this->id = $args['id'] ?? null;
       $this->titulo = $args['titulo'] ?? '';
       $this->precio = $args['precio'] ?? '';
       $this->imagen = $args['imagen'] ?? null;
       $this->descripcion = $args['descripcion'] ?? '';
       $this->habitaciones = $args['habitaciones'] ?? '';
       $this->wc = $args['wc'] ?? '';
       $this->estacionamiento =$args['estacionamiento'] ?? '';
       $this->creado = date('Y/m/d') ?? '';
       $this->vendedores_id = $args['vendedorId'] ?? '';
   }

   public function validar() {
 
      if(!$this->titulo) {
          self::$errores[] = "Debes añadir un titulo";
       }
       if(!$this->precio) {
          self::$errores[] = "Debes añadir un precio";
       }
       if (strlen(!$this->descripcion) > 50) {
          self::$errores[] = "Debes añadir descripcion y debe tener al menos 50 caracteres";
       }
       if(!$this->habitaciones) {
          self::$errores[] = "Debes añadir habitaciones";
       }
       if(!$this->wc) {
          self::$errores[] = "Debes añadir numero de wc";
       }
       if(!$this->estacionamiento) {
          self::$errores[] = "Debes añadir numero de estacionamientos";
       }
       if(!$this->vendedores_id) {
         self::$errores[] = "Debes añadir vendedor";
       }
       
       if(!$this->imagen) {
          self::$errores[]='La imagen de la propiedad es Obligatoria';
       }
  
  
       return self::$errores;
     }
 }


