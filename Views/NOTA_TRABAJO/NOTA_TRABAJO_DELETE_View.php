<?php
/*
//Clase : NOTA_TRABAJO_DELETE
//Creado el : 29-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Muestra la tabla de borrado de la nota seleccionada

*/
class NOTA_TRABAJO_DELETE{
    var $login; //declaración del atributo login
    var $Nombre; //declaración del atributo Nombre
    var $IdTrabajo; //declaración del atributo IdTrabajo
    var $NombreTrabajo; //declaración del atributo NombreTrabajo 
    var $NotaTrabajo;  //declaración del atributo NotaTrabajo
    var $PorcentajeNota;
    var $aux;
    var $case1;
function __construct($lista){
    $this->login = $lista['login'];
    $this->Nombre = $lista['Nombre'];
    $this->IdTrabajo = $lista['IdTrabajo'];
    $this->NombreTrabajo = $lista['NombreTrabajo'];
    $this->NotaTrabajo = $lista['NotaTrabajo'];
    $this->PorcentajeNota = $lista['PorcentajeNota'];
    $this->aux = ($this->NotaTrabajo * $this->PorcentajeNota)/100;
    $this->case1 = 0; 
    $this->render();
}


function render(){

  include '../Views/Header.php';   

?>
    <section class="pagina" style="min-height: 500px">
        <table class="showcurrent">
            <caption><?php echo $strings['Borrar nota'] ?></caption>
                <tr><th><?php echo $strings['Campo'] ?></th><th><?php echo $strings['Valor'] ?></th></tr>
                <tr><th><?php echo $strings['Login'] ?></th><td><?php echo $this->login ?></td></tr>
                <tr><th><?php echo $strings['Nombre'] ?></th><td><?php echo $this->Nombre ?></td></tr>
                <tr><th><?php echo $strings['IdTrabajo'] ?></th><td><?php echo $this->IdTrabajo ?></td></tr>
                <tr><th><?php echo $strings['NombreTrabajo'] ?></th><td><?php echo $this->NombreTrabajo ?></td></tr>
                <tr><th><?php echo $strings['Nota Trabajo'] ?></th><td><?php echo $this->NotaTrabajo ?></td></tr>
                <tr><th><?php echo $strings['PorcentajeNota'] ?></th><td><?php echo $this->PorcentajeNota ?></td></tr>
                <tr><th><?php echo $strings['Nota Parcial'] ?></th><td><?php echo $this->aux  ?></td></tr>
        </table>

        <form method="post" name="DELETE" action="../Controllers/NOTA_TRABAJO_Controller.php">
            <input class="del" type="text" name="login" size="<?php echo strlen($this->login); ?>" readonly value="<?php echo $this->login ?>" >
            <input class="del" type="text" name="Nombre" size="<?php echo strlen($this->Nombre); ?>" readonly  value="<?php echo $this->Nombre ?>">
            <input class="del" type="text" name="IdTrabajo" size="<?php echo strlen($this->IdTrabajo); ?>" readonly value="<?php echo $this->IdTrabajo ?>" >
            <input class="del" type="text" name="NombreTrabajo" size="<?php echo strlen($this->NombreTrabajo); ?>" readonly value="<?php echo $this->NombreTrabajo ?>" >
            <input class="del" type="text" name="NotaTrabajo"  size="<?php echo strlen($this->NotaTrabajo); ?>" readonly value="<?php echo $this->NotaTrabajo ?>">
            <input class="del" type="text" name="PorcentajeNota" size="<?php echo strlen($this->PorcentajeNota); ?>" readonly value="<?php echo $this->NombreTrabajo ?>" >
            <input class="del" type="text" name="Nota Parcial"  size="<?php echo strlen($this->aux); ?>" readonly value="<?php echo $this->aux ?>">
            <input class="del" type="text" name="case1" size="<?php echo strlen($this->case1); ?>" readonly value="<?php echo $this->case1?>">

            <div class="accionesTable" style="margin-left: 0%; float: right; margin-right: 45%; margin-top:2%">
                <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=DELETE&login=<?php echo $this->login ?>&IdTrabajo=<?php echo $this->IdTrabajo ?>&case1=<?php echo $this->case1?>"><input type="image" name="action" value="DELETE" action="#" src="../Views/images/confirmar.png" title="<?php echo $strings['Borrar Nota'] ?>" ></a>
            </div>
        </form>

        <div class="accionesTable" style="float: left;">
            <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=ALL"><input type="image" src="../Views/images/back.png" title="<?php echo $strings['Volver'] ?>"></a>  
        </div>    
    </section>
<?php

  include '../Views/Footer.php';

    }
}
?>