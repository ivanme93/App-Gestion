
<?php
/*
//Clase : ENTREGA_Model.php
//Creado el : 25-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Modelo de datos de usuarios que accede a la Base de Datos

*/

class ENTREGA_Model { //declaración de la clase
	var $login; //declaración del atributo login
	var $IdTrabajo; //atributo IdTrabajo
	var $Alias; //atributo Alias
	var $Horas; // declaración del atributo Horas
	var $Ruta; //declaración del atributo Ruta
	var $lista; // array para almacenar los datos del usuario
	var $mysqli; // declaración del atributo manejador de la bd

//Constructor de la clase

function __construct($login,$IdTrabajo, $Alias, $Horas,$Ruta){
	//asignación de valores de parámetro a los atributos de la clase
	$this->login = $login;
	$this->IdTrabajo = $IdTrabajo;
	$this->Alias = $Alias;
	$this->Horas = $Horas;
    $this->Ruta = $Ruta;


	// incluimos la funcion de acceso a la bd
	include_once '../Functions/Access_DB.php';
	// conectamos con la bd y guardamos el manejador en un atributo de la clase
	$this->mysqli = ConnectDB();

	//lista con los datos del usuario
	$this->lista = array(
			"login" => $this->login,
			"IdTrabajo"=>$this->IdTrabajo,
			"Alias"=>$this->Alias,
			"Horas"=>$this->Horas,
			"Ruta" => $this->Ruta,
			"sql" => $this->mysqli, 
			"mensaje"=> '');
} // fin del constructor



//Metodo ADD()
//Inserta en la tabla  de la bd  los valores
// de los atributos del objeto. Comprueba si la clave/s esta vacia y si 
//existe ya en la tabla

function ADD()
{


    if (($this->login <> '') && ($this->IdTrabajo <> '') && ($this->Alias <> '')){ // si el atributos not null no estan vacios

	  $existenciaU = $this->comprobarExistenciaUsuario();
	  $existenciaT = $this->comprobarExistenciaTrabajo();
	  $existenciaE = $this->comprobarExistenciaEntrega();

	  if( (is_string($existenciaU)) || (is_string($existenciaT) ) || (is_string($existenciaE)) ) {
	  			$this->lista['mensaje'] = 'ERROR: No se ha podido conectar con la base de datos';
					return $this->lista; // error en la consulta (no se ha podido conectar con la bd). Devolvemos un mensaje que el controlador manejara
	  }else{
		  if(  ($existenciaU == true) && ($existenciaT == true) && ($existenciaE == false) ){

				//construimos la sentencia sql de inserción en la bd
				$sql = "INSERT INTO ENTREGA(
				login,
				IdTrabajo,
				Alias,
				Horas,
				Ruta) VALUES(
									'$this->login',
									'$this->IdTrabajo',
									'$this->Alias',
									'$this->Horas',
									'$this->Ruta')";				

				if (!$result = $this->mysqli->query($sql)){ // si da error la ejecución de la query
						$this->lista['mensaje'] = 'ERROR: No se ha podido conectar con la base de datos';
						return $this->lista; // error en la consulta (no se ha podido conectar con la bd). Devolvemos un mensaje que el controlador manejara
				}else{ //si no da error en la insercion devolvemos mensaje de exito

					$this->lista['mensaje'] = 'Inserción realizada con éxito';
					return $this->lista; //operacion de insertado correcta
				}
		  }else{

		  	if($existenciaU == false){
		  		$this->lista['mensaje'] = 'ERROR: El login no existe'; 
				return $this->lista; 
		  	}else{
		  		if($existenciaT == false){
		  			$this->lista['mensaje'] = 'ERROR: El IdTrabajo no existe'; 
					return $this->lista; 
		  		}else{
		  			$this->lista['mensaje'] = 'ERROR: Fallo en la inserción. Ya existe la entrega'; 
					return $this->lista; 
		  		}
		  	

		  	}

		  }
		}
	}else{ //Si no se introduce un IdTrabajo
			return 'ERROR: Introduzca todos los valores de todos los campos'; // introduzca un valor para el usuario
	}
				
} // fin del metodo ADD



//funcion de destrucción del objeto: se ejecuta automaticamente
//al finalizar el script
function __destruct()
{

} // fin del metodo destruct


//funcion SEARCH: hace una búsqueda en la tabla con
//los datos proporcionados. Si van vacios devuelve todos
function SEARCH()
{ 	// construimos la sentencia de busqueda con LIKE y los atributos de la entidad

    $sql = "SELECT  
					login,
    				IdTrabajo,
    				Alias,
    				Horas,
					Ruta
       			FROM ENTREGA 
    			WHERE 
    				(
    				(login LIKE '%$this->login%') &&
    				(IdTrabajo LIKE '%$this->IdTrabajo%') &&
    				(Alias LIKE '%$this->Alias%') &&
    				(Horas LIKE '%$this->Horas%') &&
	 				(Ruta LIKE '%$this->Ruta%')
	 				
	 				)";
    				

    // si se produce un error en la busqueda mandamos el mensaje de error en la consulta
    if (!($resultado = $this->mysqli->query($sql))){
			$this->lista['mensaje'] = 'ERROR: Fallo en la consulta sobre la base de datos'; 
			return $this->lista; //
	}
    else{ // si la busqueda es correcta devolvemos el recordset resultado
		return $resultado;
	}
} // fin metodo SEARCH

// funcion DELETE()
// comprueba que exista el valor de clave por el que se va a borrar,si existe se ejecuta el borrado, sino
// se manda un mensaje de que ese valor de clave no existe
function DELETE()
{	// se construye la sentencia sql de busqueda con los atributos de la clase
    $sql = "SELECT * FROM ENTREGA WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')"; //comprobar que no hay claves iguales

    // se ejecuta la query
    $result = $this->mysqli->query($sql);
    // si existe una tupla con ese valor de clave
    if ($result->num_rows == 1)
    {
    	// se construye la sentencia sql de borrado
        $sql = "DELETE FROM ENTREGA WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
        // se ejecuta la query
        $this->mysqli->query($sql);
        // se devuelve el mensaje de borrado correcto
        $this->lista['mensaje'] = 'Borrado correctamente'; 
			return $this->lista;
    } // si no existe el IdTrabajo a borrar se devuelve el mensaje de que no existe
    else{
    	 $this->lista['mensaje'] = 'ERROR: No existe la entrega que desea borrar en la BD'; 
			return $this->lista;
		}	
} // fin metodo DELETE

// funcion RellenaDatos()
// Esta función obtiene de la entidad de la bd todos los atributos a partir del valor de la clave que esta
// en el atributo de la clase
function RellenaDatos()
{	// se construye la sentencia de busqueda de la tupla
    $sql = "SELECT * FROM ENTREGA WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
    // Si la busqueda no da resultados, se devuelve el mensaje de que no existe
    if (!($resultado = $this->mysqli->query($sql))){
		$this->lista['mensaje'] = 'ERROR: No existe en la base de datos'; 
			return $this->lista; // 
	}
    else{ // si existe se devuelve la tupla resultado
		$result = $resultado->fetch_array();
		return $result;
	}
} // fin del metodo RellenaDatos()

// funcion EDIT()
// Se comprueba que la tupla a modificar exista en base al valor de su clave primaria
// si existe se modifica
function EDIT()
{
	//Si todos los campos tienen valor
	if(
		
		$this->login <> '' && 
		$this->IdTrabajo <> '' &&
		$this->Alias <> '' ){

		// se construye la sentencia de busqueda de la tupla en la bd
	    $sql = "SELECT * FROM ENTREGA WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
	    // se ejecuta la query
	    $result = $this->mysqli->query($sql);
	    $num_rows = mysqli_num_rows($result);
	    // si el numero de filas es igual a uno es que lo encuentra

	    if ($num_rows == 1)
	    {	// se construye la sentencia de modificacion en base a los atributos de la clase
			$sql = "UPDATE ENTREGA SET 
						login = '$this->login',
						IdTrabajo = '$this->IdTrabajo',
						Alias = '$this->Alias',
						Horas = '$this->Horas',
						Ruta = '$this->Ruta'
					WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
					
			// si hay un problema con la query se envia un mensaje de error en la modificacion
	        if (!($result = $this->mysqli->query($sql))){
	        		$this->lista['mensaje'] =  'ERROR: No se ha modificado'; 
					return $this->lista; 
		    	}
		    
			else{ // si no hay problemas con la modificación se indica que se ha modificado
				$this->lista['mensaje'] =  'Modificado correctamente'; 
				return $this->lista; 
			}
	    }
	    else {// si no se encuentra la tupla se manda el mensaje de que no existe la tupla
	    	$this->lista['mensaje'] =  'ERROR: No existe en la base de datos'; 
			return $this->lista; 
			}
	}else{ //Si no se introdujeron todos los valores
		 return 'ERROR: Fallo en la modificación. Introduzca todos los valores'; 

	}
} // fin del metodo EDIT


//Funcion de SHOWALL
//Devuelve las tuplas de la BD de 10 en 10

function SHOWALL($num_tupla,$max_tuplas){

	$sql = "SELECT * FROM ENTREGA LIMIT $num_tupla, $max_tuplas";


	    // si se produce un error en la busqueda mandamos el mensaje de error en la consulta
    if (!($resultado = $this->mysqli->query($sql))){
    	$this->lista['mensaje'] =  'ERROR: Fallo en la consulta sobre la base de datos'; 
		return $this->lista; 
	}
    else{ // si la busqueda es correcta devolvemos el recordset resultado
		return $resultado;
	}
} // fin metodo SHOWALL

//funcion que devuelve el numero de tuplas de la base de datos
function contarTuplas(){
	$sql = "SELECT * FROM ENTREGA";

	$datos = $this->mysqli->query($sql);

    $total_tuplas = mysqli_num_rows($datos);

    return $total_tuplas;


}

function comprobarExistenciaUsuario(){

	$sql = "SELECT * FROM USUARIO WHERE ( login = '$this->login')";
	
  if (!($result = $this->mysqli->query($sql))){
    	return 'ERROR'; 
	}else{
		$num_rows = mysqli_num_rows($result);
		if($num_rows == 1){
			return true;
		}else{
			return false;
		}
	}
}


function comprobarExistenciaTrabajo(){
	

	$sql = "SELECT * FROM TRABAJO WHERE ( IdTrabajo = '$this->IdTrabajo')";

  if (!($result = $this->mysqli->query($sql))){
    	return  'ERROR'; 
	}else{

		$num_rows = mysqli_num_rows($result);
		if($num_rows == 1){
			return true;
		}else{
			return false;
		}
	}
}

function comprobarExistenciaEntrega(){

	$sql = "SELECT * FROM ENTREGA WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
	
  if (!($result = $this->mysqli->query($sql))){
    	return  'ERROR'; 
	}else{

		$num_rows = mysqli_num_rows($result);
		if($num_rows == 1){
			return true;
		}else{
			return false;
		}
	}
}
}//Fin clase

?>