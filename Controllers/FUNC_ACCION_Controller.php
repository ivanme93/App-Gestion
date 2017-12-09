
<?php

/*
//Script : FUNC_ACCION_Controller.php
//Creado el : 9-12-2017
//Creado por: solfamidas
//-------------------------------------------------------

Controlador que recibe las peticiones del usuario y este ejectuta las acciones pertinentes sobre el Model de datos y las vistas
*/
session_start(); //solicito trabajar con la session

include_once '../Functions/Authentication.php';

if (!IsAuthenticated()){
	header('Location:../index.php');
}
include '../Models/FUNC_ACCION_Model.php';
include '../Models/FUNCIONALIDAD_Model.php';

include '../Views/FUNCIONALIDAD/FUNCIONALIDAD_SHOWALL_View.php';
include '../Views/FUNC_ACCION/FUNC_ACCION_SHOWCURRENT_View.php';
include '../Views/FUNC_ACCION/FUNC_ACCION_EDIT_View.php';

include '../Views/MESSAGE_View.php';



// funcion para coger los datos del formulario
function get_data_form(){

	$IdFuncionalidad = null;
	$checkbox = null;
	$lista = null;
	$action = null;

	if(isset($_REQUEST['IdFuncionalidad'])){
		$IdFuncionalidad = $_REQUEST['IdFuncionalidad'];
	}
	//Si existen los checkbox
	if(isset($_REQUEST['checkbox'])){
		$checkbox = $_REQUEST['checkbox'];
		$num = count($checkbox);

		for ($i=0; $i<$num;$i++){
			$lista[$i] = $checkbox[$i]; //inserto en la lista cada uno de los IDS de los checkboxs seleccionados por el usuario
		}
	}
	

	$FUNC_ACCION = new FUNC_ACCION_Model(
		$IdFuncionalidad, 
		$lista);

	return $FUNC_ACCION;
}

//Si el usuario no elige ninguna opción
if (!isset($_REQUEST['action'])){
	$action = ''; //la acctión se pone vacía
}else{
	$action = $_REQUEST['action']; //si no, se le asigna la accion elegida

}
	// En funcion de la accion elegida
	Switch ($action){
	
		case 'EDIT':
		if (!$_POST){
			$FUNCIONALIDAD = new FUNCIONALIDAD_Model($_REQUEST['IdFuncionalidad'], '','');//crea un un FUNCIONALIDAD_Model con el IdFUNCIONALIDAD de la funcionalidad
			$propios = $FUNCIONALIDAD->rellenarAcciones();
			$todos = $FUNCIONALIDAD->todosAcciones();
			$lista = $FUNCIONALIDAD->rellenarLista();
			$resultado = new FUNC_ACCION_EDIT($lista,$propios,$todos);
		}else{

			$FUNC_ACCION = get_data_form();
			$respuesta = $FUNC_ACCION->EDIT();
			$mensaje = new MESSAGE($respuesta, '../Controllers/FUNCIONALIDAD_Controller.php'); //muestra el mensaje despues de la sentencia sql


		}
		break;
		default: //Por defecto, Se muestra la vista SHOWALL
			$FUNCIONALIDAD = new FUNCIONALIDAD_Model($_REQUEST['IdFuncionalidad'], '','');//crea un un FUNCIONALIDAD_Model con el IdFUNCIONALIDAD de la funcionalidad
			//$num_rows = $FUNCIONALIDAD->contarNumAccionesFunc();
			$recordset = $FUNCIONALIDAD->rellenarAcciones();
				$lista = $FUNCIONALIDAD->rellenarLista();

			$resultado = new FUNC_ACCION_SHOWALL($lista,$recordset);
	}

?>