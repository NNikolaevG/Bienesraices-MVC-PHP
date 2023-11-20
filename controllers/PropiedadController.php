<?php 

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController{

   public static function index(Router $router){

    $propiedades = Propiedad::all();
    $vendedores = Vendedor::all();

    $resultado =$_GET['resultado'] ?? null;

    

    $router->render('propiedades/admin', [
        'propiedades' => $propiedades,
        'resultado' => $resultado,
        'vendedores' => $vendedores

    ]);
   }

   public static function crear(Router $router){

    $propiedad = new Propiedad();
    $vendedor = Vendedor::all();
    //Arreglo con mensaje de errores en el formulario
    $errores= Propiedad::getErrores();

    //Ejecutar el codigo despues de enviar en formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
    //Crear una nueva instancia
   $propiedad = new Propiedad($_POST['propiedad']);
   
   
    //Generar un nombre unico
   
    $nombreImagen = md5( uniqid( rand(), true)) . ".jpg";
   
   
   //Setear la Imagen
   
   //Realizar un resize a la imagen con intervention
   if($_FILES['propiedad']['tmp_name']['imagen']){
   $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
   $propiedad->setImagen($nombreImagen);
   
   }

  /*  debuguear(CARPETA_IMAGENES); */

   //Validar
   $errores = $propiedad->validar();
   
   
   //Revisar que el arreglo de errores este vacio.
   
   if(empty($errores)) {
     
       //Crear carpeta
   
       if(!is_dir(CARPETA_IMAGENES)){
          mkdir(CARPETA_IMAGENES);
       }
   
   
   $image->save(CARPETA_IMAGENES . $nombreImagen);
   //Guardar en la base de datos
   
    $resultado=$propiedad->guardar();

    if($resultado) {
        header('Location: /admin?resultado=1');
    }
    
   
     }
   
   }

    $router-> render('propiedades/crear', [
        'propiedad' => $propiedad,
        'vendedores' => $vendedor,
        'errores'=> $errores
    ]);
    
   }


   public static function actualizar(Router $router){

    $id = validarOredireccionar('/admin');

    $propiedad = Propiedad::find($id);

    $errores = Propiedad::getErrores();

    $vendedor = Vendedor::all();

    //Ejecutar el codigo despues de enviar en formulario
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //Asignar los atributos
    $args = $_POST['propiedad'];
 
    $propiedad->sincronizar($args);
 
 //Validacion 
 
    $errores = $propiedad->validar();
 // Subida de archivos
 
    //Generar un nombre unico
 
    $nombreImagen = md5( uniqid( rand(), true)) . ".jpg";
 
    if($_FILES['propiedad']['tmp_name']['imagen']){
       $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
       $propiedad->setImagen($nombreImagen);
    
    }
 //Revisar que el arreglo de errores este vacio.
 
 if(empty($errores)) {
    if($_FILES['propiedad']['tmp_name']['imagen']){
    //Almacenar la imagen
    $image->save(CARPETA_IMAGENES . $nombreImagen);
    }
 
    $propiedad->guardar();
 
       
    }
 
 }
 

    $router -> render('/propiedades/actualizar', [
        'propiedad' => $propiedad,
        'errores' => $errores,
        'vendedores' => $vendedor


    ]);

   }

   public static function eliminar(Router $router){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        //Validar ID
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
     
        if($id) {
     
           $tipo = $_POST['tipo'];
     
           if(validarTipoContenido($tipo)){
            $propiedad = Propiedad::find($id);
            $propiedad->eliminar();
        }
     
     } 
     
   }
 }
}