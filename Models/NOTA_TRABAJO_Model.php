
<?php
/*
//Clase : NOTA_TRABAJO_Model.php
//Creado el : 08-12-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Modelo de datos de usuarios que accede a la Base de Datos

*/

class NOTA_TRABAJO_Model { //declaración de la clase
	var $login; 
	var $IdTrabajo; 
	var $NotaTrabajo;
	var $mysqli; 


function __construct($login,$IdTrabajo,$NotaTrabajo){
	//asignación de valores de parámetro a los atributos de la clase
	$this->login = $login;
	$this->IdTrabajo = $IdTrabajo;
	$this->NotaTrabajo = $NotaTrabajo;


	// incluimos la funcion de acceso a la bd
	include_once '../Functions/Access_DB.php';
	// conectamos con la bd y guardamos el manejador en un atributo de la clase
	$this->mysqli = ConnectDB();

	//lista con los datos del usuario
	$this->lista = array(
			"login" => $this->login,
			"Nombre" => '',
			"IdTrabajo"=>$this->IdTrabajo,
			"NombreTrabajo"=>'',
			"NotaTrabajo" => $this->NotaTrabajo,
			"sql" => $this->mysqli, 
			"mensaje"=> '');
} // fin del constructor



//Metodo ADD()
//Inserta en la tabla  de la bd  los valores
// de los atributos del objeto. Comprueba si la clave/s esta vacia y si 
//existe ya en la tabla

function ADD()
{

    if (($this->login <> '') && ($this->IdTrabajo <> '')){ // si el atributos not null no estan vacios

	  $existenciaU = $this->comprobarExistenciaUsuario();
	  $existenciaT = $this->comprobarExistenciaTrabajo();

	  if( (is_string($existenciaU)) || (is_string($existenciaT)) ) {
	  			$this->lista['mensaje'] = 'ERROR: No se ha podido conectar con la base de datos';
					return $this->lista; // error en la consulta (no se ha podido conectar con la bd). Devolvemos un mensaje que el controlador manejara
	  }else{
		  if(  ($existenciaU == true) && ($existenciaT == true)){

				//construimos la sentencia sql de inserción en la bd
				$sql = "INSERT INTO NOTA_TRABAJO(
				login,
				IdTrabajo,
				NotaTrabajo) VALUES(
									'$this->login',
									'$this->IdTrabajo',
									'$this->NotaTrabajo')";				
									
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
					N.login,
					U.Nombre,
    				N.IdTrabajo,
    				T.NombreTrabajo,
					N.NotaTrabajo
       			FROM NOTA_TRABAJO N, USUARIO U, TRABAJO T
    			WHERE 
    				(
    				(N.login LIKE '%$this->login%') &&
    				(N.IdTrabajo LIKE '%$this->IdTrabajo%') &&
	 				(N.NotaTrabajo LIKE '%$this->NotaTrabajo%') &&
	 				(N.login = U.login) &&
	 				(N.IdTrabajo = T.IdTrabajo)
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
    $sql = "SELECT * FROM NOTA_TRABAJO WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')"; //comprobar que no hay claves iguales

    // se ejecuta la query
    $result = $this->mysqli->query($sql);
    // si existe una tupla con ese valor de clave
    if ($result->num_rows == 1)
    {
    	// se construye la sentencia sql de borrado
        $sql = "DELETE FROM NOTA_TRABAJO WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
        // se ejecuta la query
        $this->mysqli->query($sql);
        // se devuelve el mensaje de borrado correcto
        $this->lista['mensaje'] = 'Borrado correctamente'; 
			return $this->lista;
    } // si no existe el IdTrabajo a borrar se devuelve el mensaje de que no existe	
} // fin metodo DELETE

// funcion RellenaDatos()
// Esta función obtiene de la entidad de la bd todos los atributos a partir del valor de la clave que esta
// en el atributo de la clase
function RellenaDatos()
{	// se construye la sentencia de busqueda de la tupla
    $sql = "SELECT * FROM NOTA_TRABAJO N, TRABAJO T
    				 WHERE (N.login = '$this->login' AND 
    				 		T.IdTrabajo = '$this->IdTrabajo')";
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
		$this->IdTrabajo <> ''){

		// se construye la sentencia de busqueda de la tupla en la bd
	    $sql = "SELECT * FROM NOTA_TRABAJO WHERE (login = '$this->login' AND IdTrabajo = '$this->IdTrabajo')";
	    // se ejecuta la query
	    $result = $this->mysqli->query($sql);
	    $num_rows = mysqli_num_rows($result);
	    // si el numero de filas es igual a uno es que lo encuentra

	    if ($num_rows == 1)
	    {	// se construye la sentencia de modificacion en base a los atributos de la clase
			$sql = "UPDATE NOTA_TRABAJO SET 
						login = '$this->login',
						IdTrabajo = '$this->IdTrabajo',
						NotaTrabajo = '$this->NotaTrabajo'
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

	$sql = "SELECT * FROM NOTA_TRABAJO N, TRABAJO T, USUARIO U
	WHERE (N.IdTrabajo=T.IdTrabajo AND U.login=N.login )
	LIMIT $num_tupla, $max_tuplas";

	    // si se produce un error en la busqueda mandamos el mensaje de error en la consulta
    if (!($resultado = $this->mysqli->query($sql))){
    	$this->lista['mensaje'] =  'ERROR: Fallo en la consulta sobre la base de datos'; 
		return $this->lista; 
	}
    else{ // si la busqueda es correcta devolvemos el recordset resultado
		return $resultado;
	}
} // fin metodo SHOWALL

function SHOWALL_User($num_tupla,$max_tuplas){
	$login = $_SESSION['login'];
	$sql = "SELECT * 
			FROM USUARIO U, NOTA_TRABAJO N, TRABAJO T
			WHERE  (U.login = '$login' AND
					N.login = U.login AND
					N.IdTrabajo = T.IdTrabajo
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

//funcion que devuelve el numero de tuplas de la base de datos
function contarTuplas(){
	$sql = "SELECT * FROM NOTA_TRABAJO";

	$datos = $this->mysqli->query($sql);

    $total_tuplas = mysqli_num_rows($datos);

    return $total_tuplas;


}
function rellenarLista(){
		$sql = "SELECT * FROM USUARIO U, TRABAJO T, NOTA_TRABAJO N 
						WHERE (U.login = '$this->login' AND
								T.IdTrabajo = '$this->IdTrabajo' AND
								N.login = '$this->login' AND N.IdTrabajo = '$this->IdTrabajo' 
						)";
		if (!($result = $this->mysqli->query($sql))){
	    	//return  'ERROR'; 
		}else{
			$row = mysqli_fetch_array($result);
			$this->lista['Nombre'] = $row['Nombre'];
			$this->lista['NombreTrabajo'] = $row['NombreTrabajo'];
			$this->lista['NotaTrabajo'] = $row['NotaTrabajo'];
		}
	return $this->lista;
}

function comprobarExistenciaUsuario(){

	$sql = "SELECT * FROM USUARIO WHERE ( login = '$this->login')";
	
//Si se produce un error en la consulta
  if (!($result = $this->mysqli->query($sql))){
    	return 'ERROR'; 
	}else{//si no se produce un error
		$num_rows = mysqli_num_rows($result);//cogemos el numero de tuplas que coinciden con la consulta
		if($num_rows == 1){ //si hay 1 tupla es que existe algun usuario
			return true; //existe el usuario
		}else{
			return false; //no existe el usuario
		}
	}
}


function comprobarExistenciaTrabajo(){
	

	$sql = "SELECT * FROM TRABAJO WHERE ( IdTrabajo = '$this->IdTrabajo')";

	//Si se produce un error en la consulta
  if (!($result = $this->mysqli->query($sql))){
    	return  'ERROR'; 
	}else{//si no se produce un error
		$num_rows = mysqli_num_rows($result);//cogemos el numero de tuplas que coinciden con la consulta
		if($num_rows == 1){//si hay 1 tupla es que existe algun trabajo
			return true; //existe el trabajo
		}else{ //no existe ningun trabajo
			return false;
		}
	}
}

function genAutoNotasET($IdTrabajo){

	//Cogemos todas las historias que pertenecen a ese trabajo
	$sql = "SELECT * FROM HISTORIA WHERE IdTrabajo = '$IdTrabajo'";

	$historias = $this->mysqli->query($sql);
	$num_historias = mysqli_num_rows($historias);

	//Cogemos todas las entregas de ese trabajo
	$sql = "SELECT * FROM ENTREGA WHERE IdTrabajo = '$IdTrabajo'";

	$entregas = $this->mysqli->query($sql);
	$num_entregas = mysqli_num_rows($entregas);

	$cont_exitos = 0; //Cuenta las inserciones realizadas con éxito

	while ($row = mysqli_fetch_array($entregas)) {
			
		$Alias = $row['Alias'];
		$login = $row['login'];

		$num_histCorrectas = 0;

		while ($row = mysqli_fetch_array($historias)) {

			$IdHistoria = $row['IdHistoria'];

			//Cogemos las evaluaciones de esa historia para ese usuario de los demás usuarios
			$sql = "SELECT * FROM EVALUACION 
						WHERE IdTrabajo = '$IdTrabajo',
								AliasEvaluado = '$Alias',
								 IdHistoria = '$IdHistoria'";
			$evaluaciones = $this->mysqli->query($sql);
			$num_evaluaciones = mysqli_num_rows($evaluaciones);

			$num_correctos = 0; //Contará las historias que están correctas sobre la entrega del login					

			while($row = mysqli_fetch_array($evaluaciones)){

				$correcto = $row['CorrectoP'];

				if($correcto == 1) $num_correctos++;
			} 

			$correcto = $num_correctos/$num_evaluaciones;

			if($correcto == 1) $num_histCorrectas++;

		}

		//Calculamos la nota total de un trabajo para un alumno, según las historias que tiene correctas y el total y lo pasamos a base 10
		$nota = (($num_histCorrectas/$num_historias)*10);

		//Insertamos la nota calculada en la BD
		$sql = "INSERT INTO NOTA_TRABAJO(
									login,
									IdTrabajo,
									NotaTrabajo) VALUES(
													'$login',
													'$IdTrabajo',
									                '$nota')";

		if ($result_insert = $this->mysqli->query($sql)) $cont_exito++;						                
	}
	//Si NO se ha realizado una inserción de nota para todos los usuarios que han efectuado una entrega para ese IdTrabajo
	if($cont_exitos != $num_entregas) return false;
	else return true;


}//Fin genAutoNotasET()

function genAutoNotaQA($IdTrabajo,$qas){

	//Cogemos todas las historias que pertenecen a ese trabajo
	$sql = "SELECT * FROM HISTORIA WHERE IdTrabajo = '$IdTrabajo'";

	$historias = $this->mysqli->query($sql);
	$num_historias = mysqli_num_rows($historias);

	$num_qas = mysqli_num_rows($qas); //Número de qas a calificar (QAs ralizadas por los alumnos)

	$cont_exitos = 0; //Cuenta las inserciones realizadas con éxito

	while ($row = mysqli_fetch_array($qas)) {
		
		$LoginEvaluador = $row['login'];

		$num_correctos = 0; //Contará las historias que el LoginEvaluador ha corregido de acuerdo con la corrección propuesta

		while ($row = mysqli_fetch_array($historias)) {

			$IdHistoria = $row['IdHistoria'];

			//Cogemos las evaluaciones de esa historia que ha hecho ese login como LoginEvaluador
			$sql = "SELECT * FROM EVALUACION 
						WHERE IdTrabajo = '$IdTrabajo',
								LoginEvaluador = '$LoginEvaluador',
								 IdHistoria = '$IdHistoria'";

			$evaluaciones = $this->mysqli->query($sql);
			//Número de Qas realizadas por alumno sobre el IdTrabajo
			$num_evaluaciones = mysqli_num_rows($evaluaciones);

			while($row = mysqli_fetch_array($evaluaciones)){
				if($row['OK'] == 1) $num_correctos++;
			}
		}
		//Calculamos la nota de QA del IdTrabajo del alumno con LoginEvaluador en base 10
		$nota = ($num_correctos/($num_historias*$num_evaluaciones))*10;

		//Insertamos la nota calculada en la BD
		$sql = "INSERT INTO NOTA_TRABAJO(
									login,
									IdTrabajo,
									NotaTrabajo) VALUES(
													'$LoginEvaluador',
													'$IdTrabajo',
									                '$nota')";

		if ($result_insert = $this->mysqli->query($sql)) $cont_exito++;
	}
	//Si NO se ha realizado una inserción de nota para todos los usuarios que han efectuado una QA para un IdTrabajo
	if($cont_exitos != $num_qas) return false;
	else return true;

}//Fin genAutoNotaQA()

function genAutoNota(){
	//Cogemos todos los trabajos
	$sql = "SELECT * FROM TRABAJO";

	$trabajos = $this->mysqli->query($sql);

	$exito = true; //Inserciones realizadas con éxito

	while($row = mysqli_fetch_array($trabajos)){//Mientras haya trabajos

		$IdTrabajo = '$IdTrabajo';

		$sql = "SELECT * FROM ENTREGA WHERE IdTrabajo = '$IdTrabajo'";

		
		if($entrega = $this->mysqli->query($sql)){
			$num_rows = mysqli_num_rows($entrega);
			//Si es una ET
			if ($num_rows > 0) {
				//Generamos las notas de las entregas asociadas a ese IdTrabajo
				$exito = $this->genAutoNotasET($IdTrabajo);
			}
			//Si es una QA
			else{
				//Indica el número de ET a la que pertenece la QA con IdTrabajo
				$indice = $IdTrabajo[strlen($IdTrabajo)-1];

				//Cogemos las entregas que se han realizado y por lo tanto tienen QA sobre ese IdTrabajo
				$sql = "SELECT * FROM ENTREGA WHERE IdTrabajo LIKE '%$indice'";
				$qas = $this->mysqli->query($sql);

				//Generamos las notas de las QAs asociadas a ese IdTrabajo
				$exito = $this->genAutoNotaQA($IdTrabajo, $qas);
			}
		}
	}	

	if($exito){
		$this->lista['mensaje'] = 'La generación automática se ha realizado correctamente para todos los trabajos de la BD';
		return $this->lista;
	}
	else{
		$this->lista['mensaje'] = 'ERROR: La generación automática de notas ha fallado';
		return $this->lista;
	}
}//Fin genAutoNota()


}//Fin clase

?>