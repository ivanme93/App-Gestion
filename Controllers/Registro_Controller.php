<?php
/*
//Script : Register.Controller.php
//Creado el : 1-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Controlador del para llevar a cabo el proceso de registro de usuarios
*/
//sesion
session_start();
include_once '../Locales/Strings_'.$_SESSION['idioma'].'.php'; //se incluye los ficheros para el multidioma

if(!isset($_POST['login'])){ //si no se introdujo el login
	include_once '../Views/Registro_View.php';
	$register = new Register();//muestra la vista de registro
}
else{
		
	include_once '../Models/USUARIO_Model.php'; //incluye el modelo de usuarios

	if($_REQUEST['action'] == 'BACK'){ //si el usuario pulsa en volver
		header('Location: ./Login_Controller.php'); //lo redirige al controlador de login

	}else{ //si envía el formulario

	$login = $_REQUEST['login'];
	$password = $_REQUEST['password'];
	$DNI = $_REQUEST['DNI'];
	$nombre = $_REQUEST['nombre'];
	$apellidos = $_REQUEST['apellidos'];
	$telefono = $_REQUEST['telefono'];
	$email = $_REQUEST['email'];
	$direccion = $_REQUEST['direccion'];
	$action = $_REQUEST['action'];

	$USUARIOS = new USUARIO_Model(
		$login, 
		$password, 
		$DNI, 
		$nombre, 
		$apellidos,
		$telefono, 
		$email, 
		$direccion);

		$respuesta = $USUARIOS->comprobarRegistro(); //comprueba que los datos están correctamente

	if ($respuesta == 'true'){ //si estan correctamente
		$respuesta = $USUARIOS->ADD(); //añade al usuaurio en la BD
		include_once '../Views/MESSAGE_View.php';
		new MESSAGE($respuesta['mensaje'], '../index.php');
		}	
	}
}

?>