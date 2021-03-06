
<?php
/*
//Clase : TRABAJO_Model.php
//Creado el : 25-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Modelo de datos de usuarios que accede a la Base de Datos
*/

class TRABAJO_Model { //declaración de la clase

	var $IdTrabajo; //atributo IdTrabajo
	var $NombreTrabajo; //atributo NombreTrabajo
	var $FechaIniTrabajo; // declaración del atributo FechaIniTrabajo
	var $FechaFinTrabajo; //declaración del atributo FechaFinTrabajo
	var $PorcentajeNota; //declaración del atributo PorcentajeNota
	var $lista; // array para almacenar los datos del usuario
	var $mysqli; // declaración del atributo manejador de la bd

//Constructor de la clase

function __construct($IdTrabajo, $NombreTrabajo, $FechaIniTrabajo,$FechaFinTrabajo,$PorcentajeNota){
	//asignación de valores de parámetro a los atributos de la clase
	$this->IdTrabajo = $IdTrabajo;
	$this->NombreTrabajo = $NombreTrabajo;
	$this->FechaIniTrabajo = $FechaIniTrabajo;
    $this->FechaFinTrabajo = $FechaFinTrabajo;
	$this->PorcentajeNota = $PorcentajeNota;

	//si la Fecha viene vacia la asignamos vacia
	if ($FechaIniTrabajo == ''){

		$this->FechaIniTrabajo = NULL;
	}
	else{ // si no viene vacia 
		if(strlen($this->FechaIniTrabajo) == 10){	//si viene la fecha entera le cambiamos el formato para que se adecue al de la bd
		$this->FechaIniTrabajo = date_format(date_create($this->FechaIniTrabajo), 'Y-m-d');

		}
	}
	//si la Fecha  viene vacia la asignamos vacia
	if ($FechaFinTrabajo == ''){
		$this->FechaFinTrabajo = NULL;
	}
	else{ // si no viene vacia 
		if(strlen($this->FechaFinTrabajo) == 10){	//si viene la fecha entera le cambiamos el formato para que se adecue al de la bd
		$this->FechaFinTrabajo = date_format(date_create($this->FechaFinTrabajo), 'Y-m-d');

		}


	}

	// incluimos la funcion de acceso a la bd
	include_once '../Functions/Access_DB.php';
	// conectamos con la bd y guardamos el manejador en un atributo de la clase
	$this->mysqli = ConnectDB();

	//lista con los datos del usuario
	$this->lista = array(
			"IdTrabajo"=>$this->IdTrabajo,
			"NombreTrabajo"=>$this->NombreTrabajo,
			"FechaIniTrabajo"=>$this->FechaIniTrabajo,
			"FechaFinTrabajo" => $this->FechaFinTrabajo,
			"PorcentajeNota" => $this->PorcentajeNota,
			"sql" => $this->mysqli, 
			"mensaje"=> '');
} // fin del constructor



//Metodo ADD()
//Inserta en la tabla  de la bd  los valores
// de los atributos del objeto. Comprueba si la clave/s esta vacia y si 
//existe ya en la tabla

function ADD()
{

    if (($this->IdTrabajo <> '')){ // si el atributo clave de la entidad no esta vacio
		// construimos el sql para buscar esa clave en la tabla
        $sql = "SELECT * FROM TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')"; //comprobar que no hay claves iguales

		if (!$result = $this->mysqli->query($sql)){ // si da error la ejecución de la query
			$this->lista['mensaje'] = 'ERROR: No se ha podido conectar con la base de datos';
			return $this->lista; // error en la consulta (no se ha podido conectar con la bd). Devolvemos un mensaje que el controlador manejara

		}
		else { // si la ejecución de la query no da error

			$num_rows = mysqli_num_rows($result);

			if ($num_rows == 0){ // miramos si el resultado de la consulta es vacio (no existe el IdTrabajo)

				//construimos la sentencia sql de inserción en la bd
				$sql = "INSERT INTO TRABAJO(
				IdTrabajo,
				NombreTrabajo,
				FechaIniTrabajo,
				FechaFinTrabajo,
				PorcentajeNota) VALUES(
									'$this->IdTrabajo',
									'$this->NombreTrabajo',
									'$this->FechaIniTrabajo',
									'$this->FechaFinTrabajo',
									'$this->PorcentajeNota')";
				 if (!($result = $this->mysqli->query($sql))){ //si da error la consulta se comrpueba el por que
					//Si no hay atributos Clave y unique duplicados es que hay campos sin completar
        			return 'ERROR: Introduzca todos los valores de todos los campos'; // introduzca un valor para el usuario
				}

    			else{ //si no da error en la insercion devolvemos mensaje de exito
					$this->lista['mensaje'] = 'Inserción realizada con éxito';
					return $this->lista; //operacion de insertado correcta
				}
			}else{ //si hay un IdTrabajo igual

	        	$this->lista['mensaje'] = 'ERROR: Fallo en la inserción. Ya existe el IdTrabajo'; 
				return $this->lista; 
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
    $sql = "SELECT  IdTrabajo,
    				NombreTrabajo,
    				FechaIniTrabajo,
					FechaFinTrabajo,
					PorcentajeNota
       			FROM TRABAJO 
    			WHERE 
    				(
    				(IdTrabajo LIKE '%$this->IdTrabajo%') &&
    				(NombreTrabajo LIKE '%$this->NombreTrabajo%') &&
    				(FechaIniTrabajo LIKE '%$this->FechaIniTrabajo%') &&
	 				(FechaFinTrabajo LIKE '%$this->FechaFinTrabajo%') &&
	 				(PorcentajeNota LIKE '%$this->PorcentajeNota%') 
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

	


    $sql = "SELECT * FROM TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
    // se ejecuta la query
    $resultado = $this->mysqli->query($sql);
    $num_rows = mysqli_num_rows($resultado);
    // si existe una tupla con ese valor de clave

    if ($num_rows == 1)
    {
    	// se construye la sentencia sql de borrado
        $sql = "DELETE FROM TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
        // se ejecuta la query
    	$resultado = $this->mysqli->query($sql);

    	$sql = "SELECT * FROM ENTREGA WHERE (IdTrabajo = '$this->IdTrabajo')";
    	if($resultado = $this->mysqli->query($sql) ){
		    $sql = "DELETE FROM ENTREGA WHERE (IdTrabajo = '$this->IdTrabajo')";
	        // se ejecuta la query
	    	$resultado = $this->mysqli->query($sql);
    	}
    	$sql = "SELECT * FROM ASIGNAC_QA WHERE (IdTrabajo = '$this->IdTrabajo')";
    	if($resultado = $this->mysqli->query($sql) ){
			$sql = "DELETE FROM ASIGNAC_QA WHERE (IdTrabajo = '$this->IdTrabajo')";
	        // se ejecuta la query
	    	$resultado = $this->mysqli->query($sql);
    	}
    	$sql = "SELECT * FROM NOTA_TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
    	if($resultado = $this->mysqli->query($sql) ){
			$sql = "DELETE FROM NOTA_TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
	        // se ejecuta la query
	    	$resultado = $this->mysqli->query($sql);
    		
    	}$sql = "SELECT * FROM EVALUACION WHERE (IdTrabajo = '$this->IdTrabajo')";
    	if($resultado = $this->mysqli->query($sql) ){
    		$sql = "DELETE FROM EVALUACION WHERE (IdTrabajo = '$this->IdTrabajo')";
	        // se ejecuta la query
	    	$resultado = $this->mysqli->query($sql);
    	}


        // se devuelve el mensaje de borrado correcto
        $this->lista['mensaje'] = 'Borrado correctamente'; 
			return $this->lista;
    } // si no existe el IdTrabajo a borrar se devuelve el mensaje de que no existe
    else{
    	 $this->lista['mensaje'] = 'ERROR: No existe el trabajo que desea borrar en la BD'; 
		return $this->lista;
		}	
} // fin metodo DELETE

// funcion RellenaDatos()
// Esta función obtiene de la entidad de la bd todos los atributos a partir del valor de la clave que esta
// en el atributo de la clase
function RellenaDatos()
{	// se construye la sentencia de busqueda de la tupla
    $sql = "SELECT * FROM TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
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
		$this->IdTrabajo <> '' &&
		$this->NombreTrabajo <> '' &&
		$this->FechaIniTrabajo <> '' &&
		$this->FechaFinTrabajo <> '' &&
		$this->PorcentajeNota <> ''){

		// se construye la sentencia de busqueda de la tupla en la bd
	    $sql = "SELECT * FROM TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
	    // se ejecuta la query
	    $result = $this->mysqli->query($sql);
	    $num_rows = mysqli_num_rows($result);
	    // si el numero de filas es igual a uno es que lo encuentra

	    if ($num_rows == 1)
	    {	// se construye la sentencia de modificacion en base a los atributos de la clase
			$sql = "UPDATE TRABAJO SET 
						IdTrabajo = '$this->IdTrabajo',
						NombreTrabajo = '$this->NombreTrabajo',
						FechaIniTrabajo = '$this->FechaIniTrabajo',
						FechaFinTrabajo = '$this->FechaFinTrabajo',
						PorcentajeNota = '$this->PorcentajeNota'
					WHERE ( IdTrabajo = '$this->IdTrabajo')";
					
			// si hay un problema con la query se envia un mensaje de error en la modificacion
	        if (!($result = $this->mysqli->query($sql))){

			        		// se construye la sentencia de busqueda de la tupla en la bd
			    $sql = "SELECT * FROM TRABAJO WHERE (IdTrabajo = '$this->IdTrabajo')";
			    // se ejecuta la query
			    $result = $this->mysqli->query($sql);
			    $num_rows = mysqli_num_rows($result);
			    $row = $result->fetch_array();

			    if( ($num_rows == 1) && ( $row['IdTrabajo'] != $this->IdTrabajo) ){ //Si devuelve 1 tupla y no coinciden los IdTrabajo
			    	$this->lista['mensaje'] = 'ERROR: Fallo en la inserción. Ya existe el IdTrabajo'; //añadir a strings
					return $this->lista;
				}
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

	$sql = "SELECT * FROM TRABAJO LIMIT $num_tupla, $max_tuplas";


	    // si se produce un error en la busqueda mandamos el mensaje de error en la consulta
    if (!($resultado = $this->mysqli->query($sql))){
    	$this->lista['mensaje'] =  'ERROR: Fallo en la consulta sobre la base de datos'; 
		return $this->lista; 
	}
    else{ // si la busqueda es correcta devolvemos el recordset resultado
		return $resultado;
	}
} // fin metodo SHOWALL
//muestra la informacion de un usuario solo
function SHOWALL_User($num_tupla,$max_tuplas){
	$login = $_SESSION['login'];
	$sql = "SELECT * 
			FROM USUARIO U, ENTREGA E, TRABAJO T
			WHERE  (U.login = '$login' AND
					E.login = U.login AND
					T.IdTrabajo = E.IdTrabajo 
					)
			LIMIT $num_tupla, $max_tuplas";
/*
	$sql = "SELECT * FROM USUARIO U, USU_GRUPO UG, GRUPO G
					WHERE (U.login = UG.login AND
							UG.IdGrupo = G.IdGrupo )
					LIMIT $num_tupla, $max_tuplas";
*/
	    // si se produce un error en la busqueda mandamos el mensaje de error en la consulta
    if (!($resultado = $this->mysqli->query($sql))){
    	$this->lista['mensaje'] =  'ERROR: Fallo en la consulta sobre la base de datos'; 
		return $this->lista; 
	}
    else{ // si la busqueda es correcta devolvemos el recordset resultado
		return $resultado;
	}
} // fin metodo SHOWALL

//lista las historias para un trabajo
function listarHistorias(){

	$sql = "SELECT * FROM TRABAJO T, HISTORIA H WHERE (T.IdTrabajo = H.IdTrabajo AND T.IdTrabajo = '$this->IdTrabajo')";
	    // si se produce un error en la busqueda mandamos el mensaje de error en la consulta

    if (!($resultado = $this->mysqli->query($sql))){
    	$this->lista['mensaje'] =  'ERROR: Fallo en la consulta sobre la base de datos'; 
		return $this->lista; 
	}
    else{ // si la busqueda es correcta devolvemos el recordset resultado
		return $resultado;
	}
}

//funcion que devuelve el numero de tuplas de la base de datos
function contarTuplas(){
	$sql = "SELECT * FROM TRABAJO";

	$datos = $this->mysqli->query($sql);

    $total_tuplas = mysqli_num_rows($datos);

    return $total_tuplas;
}
//comprueba su existe una entrega
function comprobarEntrega(){
	$login = $_SESSION['login'];
	$lista = null;

	$sql = "SELECT E.IdTrabajo FROM ENTREGA E, TRABAJO T
						WHERE(	E.IdTrabajo = T.IdTrabajo AND
								E.login = '$login'
					)";
	$datos = $this->mysqli->query($sql);
	$num_rows = mysqli_num_rows($datos);
	if($num_rows > 0){

		while($row = mysqli_fetch_array($datos)){
			$lista[$row['IdTrabajo']] =true;
		}
		return $lista;

	}else{
		return $lista;
	}
}
}
?>