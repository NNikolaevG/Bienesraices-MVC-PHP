<fieldset>
         <legend>Informacion General</legend>

         <label for="titulo">Nombre:</label>
         <input 
         type="text"
         id="nombre" 
         name="vendedor[nombre]" 
         placeholder="Nombre Vendedor" 
         value="<?php echo s($vendedor->nombre);?>">


         <label for="titulo">Apellido:</label>
         <input 
         type="text"
         id="apellido" 
         name="vendedor[apellido]" 
         placeholder="Apellido Vendedor" 
         value="<?php echo s($vendedor->apellido);?>">
         
         
         <label for="titulo">Telefono:</label>
         <input 
         type="text"
         id="telefono" 
         name="vendedor[telefono]" 
         placeholder="Telefono Vendedor" 
         value="<?php echo s($vendedor->telefono);?>">

</fieldset>