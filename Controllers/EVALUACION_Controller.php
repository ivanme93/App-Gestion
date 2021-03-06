
<?php

/*
//Script : EVALUACION_Controller.php
//Creado el : 29-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Controlador que recibe las acciones relacionadas con EVALUACION
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
if(!HavePermissions(10, $action)) {
	new MESSAGE('No tienes permisos para realizar esta accion', '../index.php');exit();
	//header('Location:../index.php'); //vuelve al index
	
}
//almacenamos un array de permidos del grupo
$permisos = listaPermisos();
$acciones = listaAcciones(10);

//Pnemos la variabla acceso  a false con la que se controla si el usuario puede ver un showall o no
$acceso=false;

include_once '../Models/EVALUACION_Model.php';
include_once '../Models/ENTREGA_Model.php';
include_once '../Models/USU_GRUPO_Model.php';
include_once '../Models/Calificacion_Model.php';

include_once '../Views/EVALUACION/EVALUACION_SHOWALL_View.php';
include_once '../Views/EVALUACION/EVALUACION_SHOWCURRENT_View.php';
include_once '../Views/EVALUACION/EVALUACION_ADD_View.php';
include_once '../Views/EVALUACION/EVALUACION_EDIT_View.php';
include_once '../Views/EVALUACION/EVALUACION_SEARCH_View.php';
include_once '../Views/EVALUACION/EVALUACION_DELETE_View.php';
include_once '../Views/MESSAGE_View.php';


include_once '../Views/EVALUACION/EVALUACION_CALIFICAR_View.php';
include_once '../Views/EVALUACION/EVALUACION_RESULTS_View.php';
include_once '../Views/EVALUACION/EVALUACION_RESULTS_QA_View.php';


// funcion para coger los datos del formulario
function get_data_form(){

	$IdTrabajo = null;
	$LoginEvaluador = null;
	$AliasEvaluado = null;
	$IdHistoria = null;
	$CorrectoA = null;
	$ComenIncorrectoA = null;
	$CorrectoP = null;
	$ComentIncorrectoP = null;
	$OK = null;
	$origen = null;
	$action = null;


	if(isset($_REQUEST['IdTrabajo'])){
	$IdTrabajo = $_REQUEST['IdTrabajo'];
	}
	if(isset($_REQUEST['LoginEvaluador'])){
	$LoginEvaluador = $_REQUEST['LoginEvaluador'];
	}
	if(isset($_REQUEST['AliasEvaluado'])){
	$AliasEvaluado = $_REQUEST['AliasEvaluado'];
	}
	if(isset($_REQUEST['IdHistoria'])){
	$IdHistoria = $_REQUEST['IdHistoria'];
	}
	if(isset($_REQUEST['CorrectoA'])){
	$CorrectoA = $_REQUEST['CorrectoA'];
	}
	if(isset($_REQUEST['ComenIncorrectoA'])){
	$ComenIncorrectoA = $_REQUEST['ComenIncorrectoA'];
	}
	if(isset($_REQUEST['CorrectoP'])){
	$CorrectoP = $_REQUEST['CorrectoP'];
	}
	if(isset($_REQUEST['ComentIncorrectoP'])){
	$ComentIncorrectoP = $_REQUEST['ComentIncorrectoP'];
	}
	if(isset($_REQUEST['OK'])){
	$OK = $_REQUEST['OK'];
	}
	if(isset($_REQUEST['origen'])){
	$origen = $_REQUEST['origen'];
	}

		$EVALUACION = new EVALUACION_Model(
		$IdTrabajo, 
		$LoginEvaluador,
		$AliasEvaluado, 
		$IdHistoria, 
		$CorrectoA,
		$ComenIncorrectoA,
		$CorrectoP,
		$ComentIncorrectoP,
		$OK);

	return $EVALUACION;


}
//Funcion para coger los datos del formulario de una evaluacion ya almacenada
function get_data_UserBD(){

	$IdTrabajo = null;
	$LoginEvaluador = null;
	$AliasEvaluado = null;
	$IdHistoria = null;
	$CorrectoA = null;
	$ComenIncorrectoA = null;
	$CorrectoP = null;
	$ComentIncorrectoP = null;
	$OK = null;
	$origen = null;
	$action = null;


	if(isset($_REQUEST['IdTrabajo'])){
	$IdTrabajo = $_REQUEST['IdTrabajo'];
	}
	if(isset($_REQUEST['LoginEvaluador'])){
	$LoginEvaluador = $_REQUEST['LoginEvaluador'];
	}
	if(isset($_REQUEST['AliasEvaluado'])){
	$AliasEvaluado = $_REQUEST['AliasEvaluado'];
	}
	if(isset($_REQUEST['IdHistoria'])){
	$IdHistoria = $_REQUEST['IdHistoria'];
	}
	if(isset($_REQUEST['CorrectoA'])){
	$CorrectoA = $_REQUEST['CorrectoA'];
	}
	if(isset($_REQUEST['ComenIncorrectoA'])){
	$ComenIncorrectoA = $_REQUEST['ComenIncorrectoA'];
	}
	if(isset($_REQUEST['CorrectoP'])){
	$CorrectoP = $_REQUEST['CorrectoP'];
	}
	if(isset($_REQUEST['ComentIncorrectoP'])){
	$ComentIncorrectoP = $_REQUEST['ComentIncorrectoP'];
	}
	if(isset($_REQUEST['OK'])){
	$OK = $_REQUEST['OK'];
	}
	if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
	}

		$EVALUACION = new EVALUACION_Model(
		$IdTrabajo, 
		$LoginEvaluador,
		$AliasEvaluado, 
		$IdHistoria, 
		$CorrectoA,
		$ComenIncorrectoA,
		$CorrectoP,
		$ComentIncorrectoP,
		$OK);

	return $EVALUACION;
}

//Si el usuario no elige ninguna opción
if (!isset($_REQUEST['action'])){
	$action = ''; //la acctión se pone vacía
}else{
	$action = $_REQUEST['action']; //si no, se le asigna la accion elegida

}

//Funcion para coger los datos de los checkbox de las evaluaciones
function getCalificarChecbox(){
	$numHistorias = 0;
	$numEvaluadores = 0;
	$IdTrabajo = null;
	$listaEvaluadores = null;
	$listaEvaluado = null;
	$evaluadores = null;
	$evaluado = null;
	$AliasEvaluado = null;
	$listaComentarios = null;

		//Si existen los evaluadores
	if(isset($_REQUEST['ComentIncorrectoP'])){
		$ComentIncorrectoP = $_POST['ComentIncorrectoP'];
		$num = count($ComentIncorrectoP);
		foreach ($ComentIncorrectoP as $key => $value) {
			$listaComentarios[$key] = $value;
			}
			
	}

	if(isset($_REQUEST['IdTrabajo'])){
		$IdTrabajo = $_REQUEST['IdTrabajo'];
	}
	if(isset($_REQUEST['numHistorias'])){
		$numHistorias = $_REQUEST['numHistorias'];
	}
	if(isset($_REQUEST['numEvaluadores'])){
		$numEvaluadores = $_REQUEST['numEvaluadores'];
	}
	if(isset($_REQUEST['AliasEvaluado'])){
		$AliasEvaluado = $_REQUEST['AliasEvaluado'];
	}
	//Si existen los evaluadores
	if(isset($_REQUEST['evaluadores'])){
		$evaluadores = $_REQUEST['evaluadores'];
		$num = count($evaluadores);
		for ($i=0; $i<$num;$i++){
			//echo $evaluadores[$i];
			$check =explode("?" ,  $evaluadores[$i]);
			$listaEvaluadores[$i] = array($check[0], $check[1] ,$check[2], $check[3], $check[4],$check[5] ); //inserto en la lista cada uno de los IDS Funcionalidad de los checkboxs seleccionados por el usuario
		}
	}
	//Si existen los evaluados
	if(isset($_REQUEST['evaluado'])){
		$evaluado = $_REQUEST['evaluado'];
		$num = count($evaluado);
		for ($i=0; $i<$num;$i++){
			//echo $i ."<--for";

			//echo $evaluados[$i];
			$check =explode("?" ,  $evaluado[$i]);
			$listaEvaluado[$check[0]] = $check[1]; //inserto en la lista cada uno de las id historias con el correctoP
		}
	}

		$CALIFICACION = new Calificacion_Model(
		$IdTrabajo, 
		$AliasEvaluado, 
		$listaEvaluadores, 
		$listaEvaluado,
		$listaComentarios,
		$numHistorias,
		$numEvaluadores);

	return $CALIFICACION;

}


	// En funcion de la accion elegida
	Switch ($action){

		case 'RESULE': //si un alumno quiere visualizar sus resultados
			$IdTrabajo = null;
			$login = null;
			$AliasEvaluado = null;

			if ((isset($_REQUEST['IdTrabajo'])) && (isset($_REQUEST['login']))) {
				$IdTrabajo = $_REQUEST['IdTrabajo'];
				$login = $_REQUEST['login'];
			}
			$EV = new ENTREGA_Model($_REQUEST['login'], $_REQUEST['IdTrabajo'], '', '', '', '', '');
			$AliasEvaluado = $EV->obtenerAlias();

			$EVALUACION = new EVALUACION_Model($IdTrabajo, '', $AliasEvaluado, '', '', '', '', '', ''); //crea una EVALUACION_Model con los campos clave del usuario y del trabajo
			$contar = $EVALUACION->contar(); //contamos los login evaluadores
			$contarHistorias = $EVALUACION->contarHistorias(); //contamos las historias
			$lista = $EVALUACION->rellenarLista();
			$lista['IdTrabajo'] = $IdTrabajo;
			$lista['AliasEvaluado'] = $AliasEvaluado;
			$listaHistorias = $EVALUACION->listarHistoriasCalificar();
			$rellenarHistorias = $EVALUACION->rellenarHistorias();

			$usuario = new EVALUACION_RESULTS($lista, $listaHistorias, $contar, $contarHistorias, $rellenarHistorias);
			$usuario->renderET();

		break;

		case 'RESULQ': //si un alumno quiere visualizar sus resultados
			$IdTrabajo = null;
			$login = null;

			if ((isset($_REQUEST['IdTrabajo'])) && (isset($_REQUEST['login']))) {
				$IdTrabajo = $_REQUEST['IdTrabajo'];
				$login = $_REQUEST['login'];
			}
			
			$Idaux = $IdTrabajo[strlen($IdTrabajo)-1];
			
			$id = "ET";
			$id .= $Idaux;
			


			$EVALUACION = new EVALUACION_Model($id, $login, '', '', '', '', '', '', ''); //crea una EVALUACION_Model con los campos clave del usuario y del trabajo
			$contarHistorias = $EVALUACION->contarHistoriasQA(); //contamos las historias
			$lista = $EVALUACION->rellenarLista();
			$lista['IdTrabajo'] = $IdTrabajo;
			$lista['LoginEvaluador'] = $login;

			$contarAlias = $EVALUACION->contarAlias();

			
			$aliasEvaluados = $EVALUACION->listaEntregasQA();
			$usuario = new EVALUACION_RESULTS_QA($lista,$aliasEvaluados, $contarAlias,$contarHistorias);

		break;
		case 'CALIF': //si es una calificacion
				if(!$_POST){

					$IdTrabajo = null;
					$AliasEvaluado = null;

					if ((isset($_REQUEST['IdTrabajo'])) && (isset($_REQUEST['AliasEvaluado']))) {
						$IdTrabajo = $_REQUEST['IdTrabajo'];
						$AliasEvaluado = $_REQUEST['AliasEvaluado'];
					}
				$EVALUACION = new EVALUACION_Model($IdTrabajo, '', $AliasEvaluado, '', '', '', '', '', ''); //crea una EVALUACION_Model con los campos clave del usuario y del trabajo
				$contar = $EVALUACION->contar();
				$contarHistorias = $EVALUACION->contarHistorias();
				$lista = $EVALUACION->rellenarLista();
				$lista['IdTrabajo'] = $IdTrabajo;
				$lista['AliasEvaluado'] = $AliasEvaluado;
				$listaHistorias = $EVALUACION->listarHistoriasCalificar();
				$rellenarHistorias = $EVALUACION->rellenarHistorias();
				//$listaLoginEvaluadores = $EVALUACION->listarLoginEvaluadores();  
				//$listaComentarios = $EVALUACION->listarComentarios();
				$usuario = new EVALUACION_CALIFICAR($lista, $listaHistorias, $contar, $contarHistorias, $rellenarHistorias);

				}else{
					$CALIFICACION =  getCalificarChecbox();
					$calif = $CALIFICACION->CALIF();
					$mensaje = new MESSAGE($calif, '../Controllers/EVALUACION_Controller.php'); //muestra el mensaje despues de la sentencia sql

				}

			break;
		case 'ADD': //Si quiere hacer un ADD
			if (!$_POST){ //si viene del showall (no es un post)
				$lista = array('IdTrabajo', 'LoginEvaluador', 'AliasEvaluado', 'IdHistoria', 'CorrectoA', 'ComenIncorrectoA', 'CorrectoP', 'ComentIncorrectoP', 'OK', 'origen');

				if( (isset($_REQUEST['IdTrabajo'])) && 
					(isset($_REQUEST['LoginEvaluador'])) && 
					(isset($_REQUEST['AliasEvaluado'])) &&
					(isset($_REQUEST['IdHistoria'])) &&
					(isset($_REQUEST['origen'])) ) {

					$EVALUACION = get_data_form(); //recibe datos
					
					$lista['IdTrabajo'] = $_REQUEST['IdTrabajo'];
					$lista['LoginEvaluador'] = $_REQUEST['LoginEvaluador'];
					$lista['AliasEvaluado'] = $_REQUEST['AliasEvaluado'];
					$lista['IdHistoria'] = $_REQUEST['IdHistoria'];
					$lista = $EVALUACION->rellenarLista();
					$lista['origen'] = $_REQUEST['origen'];

					$form = new EVALUACION_ADD($lista); //Crea la vista ADD y muestra formulario para rellenar por el usuario
				}else{
					$lista['IdTrabajo'] = '';
					$lista['LoginEvaluador'] = '';
					$lista['AliasEvaluado'] = '';
					$lista['IdHistoria'] = '';
					$lista['origen'] = '../Controllers/EVALUACION_Controller.php';
					$form = new EVALUACION_ADD($lista); //Crea la vista ADD y muestra formulario para rellenar por el usuario
				}
			}
			else{ //si viene del add 
				
				$EVALUACION = get_data_form(); //recibe datos
				$lista = $EVALUACION->ADD(); //mete datos en respuesta usuarios despues de ejecutar el add con los de EVALUACION
				$usuario = new MESSAGE($lista, '../Controllers/EVALUACION_Controller.php'); //muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'DELETE': //Si quiere hacer un DELETE
			if (!$_POST){ //viene del showall con una clave
				$lista = array('IdTrabajo', 'LoginEvaluador', 'AliasEvaluado', 'IdHistoria', 'CorrectoA', 'ComenIncorrectoA', 'CorrectoP', 'ComentIncorrectoP', 'OK', 'origen');
				$EVALUACION = new EVALUACION_Model($_REQUEST['IdTrabajo'], $_REQUEST['LoginEvaluador'], $_REQUEST['AliasEvaluado'], '', '', '', '', '', ''); //crea una EVALUACION_Model con los campos clave del usuario y del trabajo
				$lista = $EVALUACION->rellenarLista();
				if(isset($_REQUEST['origen'])){
					$lista['origen'] = $_REQUEST['origen'];
				}else{
					$lista['origen'] ='../Controllers/EVALUACION_Controller.php';
				}
				
				$usuario = new EVALUACION_DELETE($lista); //Crea la vista de DELETE con los datos del usuario
			}
			else{//si viene con un post
				$EVALUACION = get_data_UserBD(); //coge los datos del formulario del usuario que desea borrar
				$respuesta = $EVALUACION->DELETE(); //Ejecuta la funcion DELETE() en el EVALUACION_Model
				$mensaje = new MESSAGE($respuesta, '../Controllers/EVALUACION_Controller.php'); //muestra el mensaje despues de la sentencia sql
			}
			break;
		case 'EDIT': //si el usuario quiere editar	
			
				$EVALUACION = new EVALUACION_Model($_REQUEST['IdTrabajo'], $_REQUEST['LoginEvaluador'], $_REQUEST['AliasEvaluado'], $_REQUEST['IdHistoria'], '', '', '', '', ''); //crea una EVALUACION_Model con los campos clave del usuario y del trabajo
				$lista = $EVALUACION->rellenarListaIdHistoria();  //Recoge todos los atributos
				
				$grupos_user= new USU_GRUPO_Model($_SESSION['login'], ''); 
				$listaGrupos = $grupos_user->listagrupoUsuario(); //Comprobamos si es un ADMIN
				$usuario = new EVALUACION_EDIT($lista, $listaGrupos); //Crea la vista EDIT con los datos del usuario

			break;
		case 'SEARCH': //si desea realizar una busqueda
	
			if (!$_POST){
				
				$EVALUACION = new EVALUACION_SEARCH();//Crea la vista SEARCH y muestra formulario para rellenar por el usuario
			}
			else{
				$EVALUACION = get_data_UserBD(); //coge los datos del formulario del usuario que desea buscar
				$datos = $EVALUACION->SEARCH();//Ejecuta la funcion SEARCH() en el EVALUACION_Model
				$lista = array('IdTrabajo', 'LoginEvaluador', 'AliasEvaluado', 'IdHistoria', 'CorrectoA', 'ComenIncorrectoA', 'CorrectoP', 'ComentIncorrectoP', 'OK');
				$resultado = new EVALUACION_SHOWALL($lista, $datos, 0, 0, 0, 0, 'SEARCH', '../Controllers/EVALUACION_Controller.php',$acciones);//Crea la vista SHOWALL y muestra los usuarios que cumplen los parámetros de búsqueda 
			}
			break;
		case 'SHOW': //si desea ver un usuario en detalle

			if(!$_POST){
				$lista = array('IdTrabajo', 'LoginEvaluador', 'AliasEvaluado', 'IdHistoria', 'CorrectoA', 'ComenIncorrectoA', 'CorrectoP', 'ComentIncorrectoP', 'OK', 'origen');
				$EVALUACION = new EVALUACION_Model($_REQUEST['IdTrabajo'], $_REQUEST['LoginEvaluador'], $_REQUEST['AliasEvaluado'], '', '', '', '', '', ''); //crea una EVALUACION_Model con los campos clave del usuario y del trabajo
				$listaHistorias = $EVALUACION->listarHistoriasSHOWCURRENT();
				$lista = $EVALUACION->rellenarLista();

				$usuario = new EVALUACION_SHOWCURRENT($lista, $listaHistorias, $acciones); //Crea la vista SHOWCURRENT del usuario requerido
			}else{
				$EVALUACION = get_data_UserBD(); //coge los datos del formulario del usuario que desea editar
				$respuesta = $EVALUACION->EDIT(); //Ejecuta la funcion EDIT() en el EVALUACION_Model
				$listaHistorias = $EVALUACION->listarHistoriasSHOWCURRENT();
				$lista = $EVALUACION->rellenarLista();

				$usuario = new EVALUACION_SHOWCURRENT($lista, $listaHistorias,$acciones); //Crea la vista SHOWCURRENT del usuario requerido
			}
			break;
		default: //Por defecto, Se muestra la vista SHOWALL
			
			foreach ($acciones as $key => $value) {
				if($value == 'ALL'){ //si puede ver el showall
					$acceso = true; //acceso a true
				}
			}
			if($acceso == true){ //si tiene acceso, mostramos el showall
				if (!$_POST){
				$EVALUACION = new EVALUACION_Model('', '', '', '', '', '', '', '', '');//crea una EVALUACION_Model
			}
			else{
				$EVALUACION = get_data_form(); //Coge los datos del formulario
			}

			if(!isset($_REQUEST['num_pagina'])){ //Si es la 1a página del showall a mostrar
				$num_pagina = 0;
			}else{ //Si es otra página
				$num_pagina = $_REQUEST['num_pagina']; //coge el numero de página del formulario
			}
			$num_tupla = $num_pagina*10; //número de la 1º tupla a mostrar
			$max_tuplas = $num_tupla+10; // el número de tuplas a mostrar por página
			$totalTuplas = $EVALUACION->contarTuplas(); //Cuenta el número de tuplas que hay en la BD
			$datos = $EVALUACION->SHOWALL($num_tupla,$max_tuplas); //Ejecuta la funcion SHOWALL() en el EVALUACION_Model
			$lista = array('IdTrabajo', 'NombreTrabajo', 'LoginEvaluador', 'AliasEvaluado', 'IdHistoria', 'TextoHistoria', 'CorrectoA', 'ComenIncorrectoA', 'CorrectoP', 'ComentIncorrectoP', 'OK');
			$UsuariosBD = new EVALUACION_SHOWALL($lista, $datos, $num_tupla, $max_tuplas, $totalTuplas, $num_pagina, 'ALL', '../Controllers/EVALUACION_Controller.php',$acciones ); //Crea la vista SHOWALL de los usuarios de la BD	
			}else{
				if (!$_POST){
					$EVALUACION = new EVALUACION_Model('', '', '', '', '', '', '', '', '');//crea una EVALUACION_Model
				}
				else{
					$EVALUACION = get_data_form(); //Coge los datos del formulario
				}

				if(!isset($_REQUEST['num_pagina'])){ //Si es la 1a página del showall a mostrar
					$num_pagina = 0;
				}else{ //Si es otra página
					$num_pagina = $_REQUEST['num_pagina']; //coge el numero de página del formulario
				}
				$num_tupla = $num_pagina*10; //número de la 1º tupla a mostrar
				$max_tuplas = $num_tupla+10; // el número de tuplas a mostrar por página
				$totalTuplas = $EVALUACION->contarTuplas(); //Cuenta el número de tuplas que hay en la BD
				$datos = $EVALUACION->SHOWALL_User($num_tupla,$max_tuplas); //Ejecuta la funcion SHOWALL() en el EVALUACION_Model
				$lista = array('IdTrabajo', 'NombreTrabajo', 'LoginEvaluador', 'AliasEvaluado', 'IdHistoria', 'TextoHistoria', 'CorrectoA', 'ComenIncorrectoA', 'CorrectoP', 'ComentIncorrectoP', 'OK');
				$GruposBD = new EVALUACION_SHOWALL($lista, $datos, $num_tupla, $max_tuplas, $totalTuplas, $num_pagina, 'ALL', '../Controllers/GRUPO_Controller.php',$acciones); //Crea la vista SHOWALL de los grupos de la BD
			}	
		}
	}

?>