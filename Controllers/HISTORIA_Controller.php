<?php
/*
	Autor: SOLFAMIDAS
	Fecha de creación: 26/11/2017
	Descripción: Controlador para la entidad HISTORIA.

*/

session_start(); //solicito trabajar con la session

include_once '../Functions/Authentication.php';

if (!IsAuthenticated()){
	//header('Location:../index.php');
	header('Location:../index.php');
}
include '../Models/HISTORIA_Model.php';

include '../Views/HISTORIA/HISTORIA_SHOWALL_View.php';
include '../Views/HISTORIA/HISTORIA_SHOWCURRENT_View.php';
include '../Views/HISTORIA/HISTORIA_ADD_View.php';
include '../Views/HISTORIA/HISTORIA_EDIT_View.php';
include '../Views/HISTORIA/HISTORIA_SEARCH_View.php';
include '../Views/HISTORIA/HISTORIA_DELETE_View.php';
include '../Views/MESSAGE_View.php';



// funcion para coger los datos del formulario
function get_data_form(){

	$IdTrabajo = $_REQUEST['IdTrabajo'];
	$IdHistoria = $_REQUEST['IdHistoria'];
	$TextoHistoria = $_REQUEST['TextoHistoria'];
	
	$action = $_REQUEST['action'];

	$HISTORIA = new HISTORIA_Model(
		$IdTrabajo, 
		$IdHistoria, 
		$TextoHistoria);

	return $HISTORIA;
}

//Funcion para coger los datos del formulario de un usuario ya almacenado
function get_data_UserBD(){

	$IdTrabajo = $_REQUEST['IdTrabajo'];
	$IdHistoria = $_REQUEST['IdHistoria'];
	$TextoHistoria = $_REQUEST['TextoHistoria'];

	$action = $_REQUEST['action'];

	$HISTORIA = new HISTORIA_Model(
		$IdTrabajo, 
		$IdHistoria, 
		$TextoHistoria);

	return $HISTORIA;
}

//Si el usuario no elige ninguna opción
if (!isset($_REQUEST['action'])){
	$action = ''; //la acctión se pone vacía
}else{
	$action = $_REQUEST['action']; //si no, se le asigna la accion elegida

}


	// En funcion de la accion elegida
	Switch ($action){
		case 'ADD': //Si quiere hacer un ADD
			if (!$_POST){ //si viene del showall (no es un post)

				$form = new HISTORIA_ADD(); //Crea la vista ADD y muestra formulario para rellenar por el usuario
			}
			else{ //si viene del add 

				$HISTORIA = get_data_form(); //recibe datos
				$lista = $HISTORIA->ADD(); //mete datos en respuesta usuarios despues de ejecutar el add con los de HISTORIA
				$usuario = new MESSAGE($lista, '../Controllers/HISTORIA_Controller.php'); //muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'DELETE': //Si quiere hacer un DELETE
			if (!$_POST){ //viene del showall con una clave
				$HISTORIA = new HISTORIA_Model($_REQUEST['IdTrabajo'], $_REQUEST['IdHistoria'],''); //crea un un HISTORIA_Model con el IdTrabajo y el IdHistoria
				$valores = $HISTORIA->RellenaDatos(); //completa el resto de atributos a partir de la clave
				$usuario = new HISTORIA_DELETE($valores); //Crea la vista de DELETE con los datos del usuario
			}
			else{//si viene con un post
				$HISTORIA = get_data_UserBD(); //coge los datos del formulario del usuario que desea borrar
				$respuesta = $HISTORIA->DELETE(); //Ejecuta la funcion DELETE() en el HISTORIA_Model
				$mensaje = new MESSAGE($respuesta, '../Controllers/HISTORIA_Controller.php'); //muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'EDIT': //si el usuario quiere editar	
			if (!$_POST){
				$HISTORIA = new HISTORIA_Model($_REQUEST['IdTrabajo'],  $_REQUEST['IdHistoria'],''); //crea un un HISTORIA_Model con el IdTrabajo del usuario 
				$datos = $HISTORIA->RellenaDatos();  //A partir del IdTrabajo recoge todos los atributos
				$usuario = new HISTORIA_EDIT($datos); //Crea la vista EDIT con los datos del usuario
			}
			else{
				$HISTORIA = get_data_UserBD(); //coge los datos del formulario del usuario que desea editar
				$respuesta = $HISTORIA->EDIT(); //Ejecuta la funcion EDIT() en el HISTORIA_Model
				$mensaje = new MESSAGE($respuesta, '../Controllers/HISTORIA_Controller.php');//muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'SEARCH': //si desea realizar una busqueda
			if (!$_POST){
				$HISTORIA = new HISTORIA_SEARCH();//Crea la vista SEARCH y muestra formulario para rellenar por el usuario
			}
			else{
				$HISTORIA = get_data_UserBD(); //coge los datos del formulario del usuario que desea buscar
				$datos = $HISTORIA->SEARCH();//Ejecuta la funcion SEARCH() en el HISTORIA_Model
				$lista = array('IdTrabajo','IdHistoria','TextoHistoria');
				$resultado = new HISTORIA_SHOWALL($lista, $datos, 0, 0, 0, 0, 'SEARCH', '../Controllers/HISTORIA_Controller.php');//Crea la vista SHOWALL y muestra los usuarios que cumplen los parámetros de búsqueda 
			}
			break;
		case 'SHOWCURRENT': //si desea ver un usuario en detalle
			$HISTORIA = new HISTORIA_Model($_REQUEST['IdTrabajo'],  $_REQUEST['IdHistoria'],'');//crea un un HISTORIA_Model con el IdTrabajo del usuario 
			$tupla = $HISTORIA->RellenaDatos();//A partir del IdTrabajo recoge todos los atributos
			$usuario = new HISTORIA_SHOWCURRENT($tupla); //Crea la vista SHOWCURRENT del usuario requerido
			break;
		default: //Por defecto, Se muestra la vista SHOWALL
			if (!$_POST){
				$HISTORIA = new HISTORIA_Model('', '','');//crea un un HISTORIA_Model con el IdTrabajo del usuario 
			}
			else{
				$HISTORIA = get_data_form(); //Coge los datos del formulario
			}

			if(!isset($_REQUEST['num_pagina'])){ //Si es la 1a página del showall a mostrar
				$num_pagina = 0;
			}else{ //Si es otra página
				$num_pagina = $_REQUEST['num_pagina']; //coge el numero de página del formulario
			}
			$num_tupla = $num_pagina*10; //número de la 1º tupla a mostrar
			$max_tuplas = $num_tupla+10; // el número de tuplas a mostrar por página
			$totalTuplas = $HISTORIA->contarTuplas(); //Cuenta el número de tuplas que hay en la BD
			$datos = $HISTORIA->SHOWALL($num_tupla,$max_tuplas); //Ejecuta la funcion SHOWALL() en el HISTORIA_Model
			$lista = array('IdTrabajo', 'IdHistoria', 'TextoHistoria');
			$UsuariosBD = new HISTORIA_SHOWALL($lista, $datos, $num_tupla, $max_tuplas, $totalTuplas, $num_pagina, 'SHOWALL', '../Controllers/HISTORIA_Controller.php'); //Crea la vista SHOWALL de los usuarios de la BD	
	}

?>