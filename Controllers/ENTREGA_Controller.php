
<?php

/*
//Script : ENTREGA_Controller.php
//Creado el : 29-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Controlador que recibe las acciones relacionadas con ENTREGA
*/
session_start(); //solicito trabajar con la session

include_once '../Functions/Authentication.php';
include_once '../Functions/ACL.php';
include_once '../Views/MESSAGE_View.php';

//Si no esta autenticado se redirije al index de la página
if (!IsAuthenticated()){
	header('Location:../index.php');

}else{//Si está autenticado

if(isset($_REQUEST["action"]))  {//Si trae acción, se almacena el valor en la variable action
	$action = $_REQUEST["action"];
}else{//Si no trae accion

	$action = '';
}

//Si no tiene permisos para acceder a este controlador con la accion que trae
if(!HavePermissions(8, $action)) {
	new MESSAGE('No tienes permisos para realizar esta accion', '../index.php');exit();
	//header('Location:../index.php'); //vuelve al index
	

}
//almacenamos un array de permidos del grupo
$permisos = listaPermisos();
$acciones = listaAcciones(8);

//Pnemos la variabla acceso  a false con la que se controla si el usuario puede ver un showall o no
$acceso=false;

include_once '../Models/ENTREGA_Model.php';

include_once '../Views/ENTREGA/ENTREGA_SHOWALL_View.php';
include_once '../Views/ENTREGA/ENTREGA_SHOWCURRENT_View.php';
include_once '../Views/ENTREGA/ENTREGA_ADD_View.php';
include_once '../Views/ENTREGA/ENTREGA_EDIT_View.php';
include_once '../Views/ENTREGA/ENTREGA_SEARCH_View.php';
include_once '../Views/ENTREGA/ENTREGA_DELETE_View.php';
include_once '../Views/MESSAGE_View.php';



// funcion para coger los datos del formulario
function get_data_form(){

	$login = null;
	$IdTrabajo = null;
	$Alias = null;
	$Horas = null;
	$Ruta = null;
	$origen = null;
	$action = null;
	$Nombre = null;
	$NombreTrabajo = null;

	if(isset($_REQUEST['login'])){
	$login = $_REQUEST['login'];
	}
	if(isset($_REQUEST['IdTrabajo'])){
	$IdTrabajo = $_REQUEST['IdTrabajo'];
	}
	if(isset($_REQUEST['Alias'])){
	$Alias = $_REQUEST['Alias'];
	}
	if(isset($_REQUEST['Horas'])){
	$Horas = $_REQUEST['Horas'];
	}
	if(isset($_REQUEST['Nombre'])){
	$Nombre = $_REQUEST['Nombre'];
	}
	if(isset($_REQUEST['NombreTrabajo'])){
	$NombreTrabajo = $_REQUEST['NombreTrabajo'];
	}
	if(isset($_FILES['Ruta'])){
		if($_FILES['Ruta']['tmp_name'] <> ''){ //Si  fich tiene una ruta origen
			$fich = $_FILES['Ruta']['name'];
			$ruta = $_FILES['Ruta']['tmp_name'];
			$Ruta = "../Files/".$fich;

			move_uploaded_file($ruta, $Ruta); //se mueve  fich al directorio destino

		}else{ //si no tiene ruta origen
			$Ruta= '';
		}
}

	if(isset($_REQUEST['origen'])){
	$origen = $_REQUEST['origen'];
	}

		$ENTREGA = new ENTREGA_Model(
		$login,
		$IdTrabajo, 
		$Alias, 
		$Horas, 
		$Ruta,
		$Nombre,
		$NombreTrabajo);

	return $ENTREGA;


}
//Funcion para coger los datos del formulario de un usuario ya almacenado
function get_data_UserBD(){

	$login = null;
	$IdTrabajo = null;
	$Alias = null;
	$Horas = null;
	$Ruta = null;
	$origen = null;
	$Nombre = null;
	$NombreTrabajo = null;

	$action = null;


	if(isset($_REQUEST['login'])){
	$login = $_REQUEST['login'];
	}
	if(isset($_REQUEST['IdTrabajo'])){
	$IdTrabajo = $_REQUEST['IdTrabajo'];
	}
	if(isset($_REQUEST['Alias'])){
	$Alias = $_REQUEST['Alias'];
	}
	if(isset($_REQUEST['Horas'])){
	$Horas = $_REQUEST['Horas'];
	}
	if(isset($_REQUEST['Nombre'])){
	$Nombre = $_REQUEST['Nombre'];
	}
	if(isset($_REQUEST['NombreTrabajo'])){
	$NombreTrabajo = $_REQUEST['NombreTrabajo'];
	}
	if(isset($_FILES['newRuta']) && isset($_REQUEST['Ruta'])){ //si viene del formulario edit
		if($_FILES['newRuta']['tmp_name'] <> ''){ //Si la fich tiene una ruta origen
			$fich = $_FILES['newRuta']['name'];
			$ruta = $_FILES['newRuta']['tmp_name'];
			
			$Ruta = "../Files/".$fich;

			move_uploaded_file($ruta, $Ruta); //se mueve la fich al directorio destino

		}else{ //si no tiene ruta origen
			$Ruta= $_REQUEST['Ruta'];
		}
	}else{ //si viene del search
		if(isset($_REQUEST['Ruta']) && !isset($_FILES['newRuta'])) {
			$Ruta= $_REQUEST['Ruta'];
		}
	}
	if(isset($_REQUEST['origen'])){
	$origen = $_REQUEST['origen'];
	}

		$ENTREGA = new ENTREGA_Model(
		$login,
		$IdTrabajo, 
		$Alias, 
		$Horas, 
		$Ruta,
		$Nombre,
		$NombreTrabajo);

	return $ENTREGA;
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
				$lista = array('login', 'Nombre','IdTrabajo', 'NombreTrabajo', 'Alias', 'origen');

				if( (isset($_REQUEST['login'])) && 
					(isset($_REQUEST['IdTrabajo'])) && 
					(isset($_REQUEST['origen'])) ) {

					$ENTREGA = get_data_form(); //recibe datos
					
					$lista['login'] = $_REQUEST['login'];
					$lista['IdTrabajo'] = $_REQUEST['IdTrabajo'];
					$lista = $ENTREGA->rellenarLista();
					$lista['Alias'] = $ENTREGA->generadorAlias();
					$lista['origen'] = $_REQUEST['origen'];

					$form = new ENTREGA_ADD($lista); //Crea la vista ADD y muestra formulario para rellenar por el usuario
				}else{
					$lista['login'] = '';
					$lista['origen'] = '../Controllers/ENTREGA_Controller.php';
					$form = new ENTREGA_ADD($lista); //Crea la vista ADD y muestra formulario para rellenar por el usuario
				}
			}
			else{ //si viene del add 
				$ENTREGA = get_data_form(); //recibe datos
				$lista = $ENTREGA->ADD(); //mete datos en respuesta usuarios despues de ejecutar el add con los de ENTREGA
				$usuario = new MESSAGE($lista, '../Controllers/ENTREGA_Controller.php'); //muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'DELETE': //Si quiere hacer un DELETE
			if (!$_POST){ //viene del showall con una clave
				$lista = array('login', 'Nombre','IdTrabajo', 'NombreTrabajo', 'Alias','NotaTrabajo', 'origen');
				$ENTREGA = new ENTREGA_Model($_REQUEST['login'],$_REQUEST['IdTrabajo'], '', '','','',''); //crea un un ENTREGA_Model con el IdTrabajo y el login del usuario
				$lista = $ENTREGA->rellenarLista();
				if(isset($_REQUEST['origen'])){
					$lista['origen'] = $_REQUEST['origen'];
				}else{
					$lista['origen'] ='../Controllers/ENTREGA_Controller.php';
				}
				//$tupla = $ENTREGA->RellenaDatos();//A partir del IdTrabajo recoge todos los atributos
				$usuario = new ENTREGA_DELETE($lista); //Crea la vista de DELETE con los datos del usuario
			}
			else{//si viene con un post
				$ENTREGA = get_data_UserBD(); //coge los datos del formulario del usuario que desea borrar
				$respuesta = $ENTREGA->DELETE(); //Ejecuta la funcion DELETE() en el ENTREGA_Model
				$mensaje = new MESSAGE($respuesta, '../Controllers/ENTREGA_Controller.php'); //muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'EDIT': //si el usuario quiere editar	
			if (!$_POST){
				$ENTREGA = new ENTREGA_Model($_REQUEST['login'],$_REQUEST['IdTrabajo'],'', '','','',''); //crea un un ENTREGA_Model con el IdTrabajo del usuario 
				$lista = $ENTREGA->rellenarLista();  //A partir del IdTrabajo recoge todos los atributos
				$usuario = new ENTREGA_EDIT($lista); //Crea la vista EDIT con los datos del usuario
			}
			else{
				$ENTREGA = get_data_UserBD(); //coge los datos del formulario del usuario que desea editar
				$respuesta = $ENTREGA->EDIT(); //Ejecuta la funcion EDIT() en el ENTREGA_Model
				$mensaje = new MESSAGE($respuesta, '../Controllers/ENTREGA_Controller.php');//muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'SEARCH': //si desea realizar una busqueda
			if (!$_POST){
				$ENTREGA = new ENTREGA_SEARCH();//Crea la vista SEARCH y muestra formulario para rellenar por el usuario
			}
			else{
				$ENTREGA = get_data_UserBD(); //coge los datos del formulario del usuario que desea buscar
				$datos = $ENTREGA->SEARCH();//Ejecuta la funcion SEARCH() en el ENTREGA_Model
				$lista = array('login', 'IdTrabajo','Alias','Horas','Ruta');
				$resultado = new ENTREGA_SHOWALL($lista, $datos, 0, 0, 0, 0, 'SEARCH', '../Controllers/ENTREGA_Controller.php',$acciones);//Crea la vista SHOWALL y muestra los usuarios que cumplen los parámetros de búsqueda 
			}
			break;
		case 'SHOW': //si desea ver un usuario en detalle
			$lista = array('login', 'Nombre','IdTrabajo', 'NombreTrabajo', 'Alias','NotaTrabajo', 'origen');
			$ENTREGA = new ENTREGA_Model($_REQUEST['login'],$_REQUEST['IdTrabajo'], '', '','','',''); //crea un un ENTREGA_Model con el IdTrabajo del usuario
			$lista = $ENTREGA->rellenarLista();
			//$tupla = $ENTREGA->RellenaDatos();//A partir del IdTrabajo recoge todos los atributos
			$usuario = new ENTREGA_SHOWCURRENT($lista,$permisos); //Crea la vista SHOWCURRENT del usuario requerido
			break;

		case 'ADDAL': //Añadir una entrega como alumno
		if (!$_POST){ //si viene del showall (no es un post)
				$lista = array('login', 'Nombre','IdTrabajo', 'NombreTrabajo', 'Alias', 'origen');

				if( (isset($_REQUEST['login'])) && 
					(isset($_REQUEST['IdTrabajo'])) && 
					(isset($_REQUEST['origen'])) ) {

					$ENTREGA = get_data_form(); //recibe datos
					
					$lista['login'] = $_REQUEST['login'];
					$lista['IdTrabajo'] = $_REQUEST['IdTrabajo'];
					$lista = $ENTREGA->rellenarLista();
					$lista['Alias'] = $ENTREGA->generadorAlias();
					$lista['origen'] = $_REQUEST['origen'];

					$form = new ENTREGA_ADD($lista); //Crea la vista ADD y muestra formulario para rellenar por el usuario
					$form->renderLogin();
					exit();
				}else{
					$lista['login'] = '';
					$lista['origen'] = '../Controllers/TRABAJO_Controller.php';
					$form = new ENTREGA_ADD($lista); //Crea la vista ADD y muestra formulario para rellenar por el usuario
				}
			}else{ //si viene del add 
				$ENTREGA = get_data_form(); //recibe datos
				$lista = $ENTREGA->ADD(); //mete datos en respuesta usuarios despues de ejecutar el add con los de ENTREGA
				$usuario = new MESSAGE($lista, '../Controllers/TRABAJO_Controller.php'); //muestra el mensaje despues de la sentencia sql
				exit();

			}

		default: //Por defecto, Se muestra la vista SHOWALL
			
			//recorremos el array de permisos
			foreach ($acciones as $key => $value) {
				if($value == 'ALL'){ //si puede ver el showall
					$acceso = true; //acceso a true
				}
			}
			if($acceso == true){ //si tiene acceso, mostramos el showall
				if (!$_POST){
					$ENTREGA = new ENTREGA_Model('','','', '','','','');//crea un un ENTREGA_Model con el IdTrabajo del usuario 
				}
				else{
					$ENTREGA = get_data_form(); //Coge los datos del formulario
				}

				if(!isset($_REQUEST['num_pagina'])){ //Si es la 1a página del showall a mostrar
					$num_pagina = 0;
				}else{ //Si es otra página
					$num_pagina = $_REQUEST['num_pagina']; //coge el numero de página del formulario
				}
				$num_tupla = $num_pagina*10; //número de la 1º tupla a mostrar
				$max_tuplas = $num_tupla+10; // el número de tuplas a mostrar por página
				$totalTuplas = $ENTREGA->contarTuplas(); //Cuenta el número de tuplas que hay en la BD
				$datos = $ENTREGA->SHOWALL($num_tupla,$max_tuplas); //Ejecuta la funcion SHOWALL() en el ENTREGA_Model
				$lista = array('login','IdTrabajo', 'Alias', 'Horas','Ruta');
				$UsuariosBD = new ENTREGA_SHOWALL($lista, $datos, $num_tupla, $max_tuplas, $totalTuplas, $num_pagina, 'SHOWALL', '../Controllers/ENTREGA_Controller.php',$acciones); //Crea la vista SHOWALL de los usuarios de la BD	
			}else{
				if (!$_POST){
					$ENTREGA = new ENTREGA_Model('','','', '','','','');//crea un un ENTREGA_Model con el IdTrabajo del usuario 
				}
				else{
					$ENTREGA = get_data_form(); //Coge los datos del formulario
				}

				if(!isset($_REQUEST['num_pagina'])){ //Si es la 1a página del showall a mostrar
					$num_pagina = 0;
				}else{ //Si es otra página
					$num_pagina = $_REQUEST['num_pagina']; //coge el numero de página del formulario
				}
				$num_tupla = $num_pagina*10; //número de la 1º tupla a mostrar
				$max_tuplas = $num_tupla+10; // el número de tuplas a mostrar por página
				$totalTuplas = $ENTREGA->contarTuplas(); //Cuenta el número de tuplas que hay en la BD
				$datos = $ENTREGA->SHOWALL_User($num_tupla,$max_tuplas); //Ejecuta la funcion SHOWALL() en el ENTREGA_Model
				$lista = array('login','IdTrabajo', 'Alias', 'Horas','Ruta');
				$UsuariosBD = new ENTREGA_SHOWALL($lista, $datos, $num_tupla, $max_tuplas, $totalTuplas, $num_pagina, 'ALL', '../Controllers/ENTREGA_Controller.php',$acciones); //Crea la vista SHOWALL de los usuarios de la BD	
			}
		}
	}

?>