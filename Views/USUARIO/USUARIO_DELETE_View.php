<?php
/*
//Clase : USUARIO_DELETE
//Creado el : 24-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Muestra la tabla de borrado del usuario seleccionado

*/
class USUARIO_DELETE{

	  var $login; //declaración del atributo login del usuario
    var $password; //declaración del atributo password del usuario
    var $DNI; //declaración del atributo DNI del usuario
    var $nombre; //declaración del atributo nombre
    var $apellidos; //declaración del atributo apellidos
    var $telefono; //declaración del atributo telefono
    var $email; //declaración del atributo email
    var $direccion; //declaración del atributo direccion


function __construct($tupla){

	//asignación de valores de parámetro a los atributos de la clase
	$this->login = $tupla['login'];
  $this->DNI = $tupla['DNI'];
	$this->nombre = $tupla['Nombre'];
	$this->apellidos = $tupla['Apellidos'];
	$this->telefono = $tupla['Telefono'];
	$this->email = $tupla['Correo'];
  $this->direccion = $tupla['Direccion'];

  $this->render();
}

//funcion que muestra los datos al usuario

function render(){
    include '../Views/Header.php';

?>
     <section class="pagina" style="min-height: 900px">

           <table class="showcurrent">
             <caption><?php echo $strings['Borrar Usuario'] ?></caption>
                <tr><th><?php echo $strings['Campo'] ?></th><th><?php echo $strings['Valor'] ?></th></tr>
                <tr><th><?php echo $strings['Login'] ?></th><td><?php echo $this->login ?></td></tr>
                <tr><th><?php echo $strings['DNI'] ?></th><td><?php echo $this->DNI ?></td></tr>
                <tr><th><?php echo $strings['Nombre'] ?></th><td><?php echo $this->nombre ?></td></tr>
                <tr><th><?php echo $strings['Apellidos'] ?></th><td><?php echo $this->apellidos ?></td></tr>
                <tr><th><?php echo $strings['Telefono'] ?></th><td><?php echo $this->telefono ?></td></tr>
                <tr><th><?php echo $strings['Email'] ?></th><td><?php echo $this->email ?></td></tr>
                <tr><th><?php echo $strings['Direccion'] ?></th><td><?php echo $this->direccion ?></td></tr>
            </table>


       <form method="post" name="DELETE" action="../Controllers/USUARIO_Controller.php" enctype="multipart/form-data" >
                <input class="del" type="text" name="login" size="<?php echo strlen($this->login); ?>" readonly value="<?php echo $this->login ?>" >
                <input class="del" type="text" name="DNI" size="9" readonly value="<?php echo $this->DNI ?>">
                <input class="del" type="text" name="nombre" size="<?php echo strlen($this->nombre); ?>" readonly value="<?php echo $this->nombre ?>">
                <input class="del" type="text"  name="apellidos" size="<?php echo strlen($this->apellidos); ?>" readonly value="<?php echo $this->apellidos ?>" >
                <input class="del" type="text" name="telefono" size="11" readonly  value="<?php echo $this->telefono ?>">
                <input class="del" type="email" name="email" size="<?php echo strlen($this->email); ?> " placeholder="ejemplo@email.com" readonly value="<?php echo $this->email?>" >
                <input class="del" type="text" name="direccion" size="<?php echo strlen($this->direccion); ?>" readonly value="<?php echo $this->direccion ?>">

                  <div class="accionesTable" style="margin-left: 0%; float: right; margin-right: 45%; margin-top: 2%;">

                    <a href="../Controllers/USUARIO_Controller.php?action=DELETE&login=<?php echo $this->login ?>"><input type="image" name="action" value="DELETE" action="#" src="../Views/images/confirmar.png" title="<?php echo $strings['Borrar Usuario'] ?>" ></a>
                    </div>
             </form>

                  <div class="accionesTable" style="float: left;">
                    <a href="../Controllers/USUARIO_Controller.php?action=ALL"><input type="image" src="../Views/images/back.png" title="<?php echo $strings['Volver'] ?>"></a>  
                    </div>    
		</section>
<?php

  include '../Views/Footer.php';

    }
}
?>