<?php
/*
//Clase : NOTA_TRABAJO_SHOWCURRENT
//Creado el : 29-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Muestra la tabla en detalle de la nota seleccionada 

*/
class NOTA_TRABAJO_SHOWCURRENT{
    var $login; //declaración del atributo login
    var $Nombre; //declaración del atributo Nombre
    var $IdTrabajo; //declaración del atributo IdTrabajo
    var $NombreTrabajo; //declaración del atributo NombreTrabajo 
    var $NotaTrabajo;  //declaración del atributo NotaTrabajo

function __construct($lista){
    $this->login = $lista['login'];
    $this->Nombre = $lista['Nombre'];
    $this->IdTrabajo = $lista['IdTrabajo'];
    $this->NombreTrabajo = $lista['NombreTrabajo'];
    $this->NotaTrabajo = $lista['NotaTrabajo'];
    $this->PorcentajeNota = $lista['PorcentajeNota'];

    $this->render();
}

function render(){

    include '../Views/Header.php';

?>
    <section class="pagina" style="min-height: 900px">
        <table class="showcurrent">
            <caption><?php echo $strings['Nota'] ?></caption>
                <tr><th><?php echo $strings['Campo'] ?></th><th><?php echo $strings['Valor'] ?></th></tr>
                <tr><th><?php echo $strings['Login'] ?></th><td><?php echo $this->login ?></td></tr>
                <tr><th><?php echo $strings['Nombre'] ?></th><td><?php echo $this->Nombre ?></td></tr>
                <tr><th><?php echo $strings['IdTrabajo'] ?></th><td><?php echo $this->IdTrabajo ?></td></tr>
                <tr><th><?php echo $strings['NombreTrabajo'] ?></th><td><?php echo $this->NombreTrabajo ?></td></tr>
                <tr><th><?php echo $strings['Nota Trabajo'] ?></th><td><?php echo $this->NotaTrabajo ?></td></tr>
                <tr><th><?php echo $strings['PorcentajeNota'] ?></th><td><?php echo $this->PorcentajeNota ?></td></tr>
                <tr><th><?php echo $strings['Nota Parcial'] ?></th><td><?php $aux = $this->NotaTrabajo*$this->PorcentajeNota; echo $aux  ?></td></tr>
                <tr><a href="../Controllers/NOTA_TRABAJO_Controller.php?action=RESUL&login=<?php echo $lista["login"]?>&IdTrabajo=<?php echo $lista["IdTrabajo"]?>"><input type="image" name="action" value="SHOWALL" src="../Views/images/resultado.png" title="<?php echo $strings['Ver correccion'] ?>"></a>
                    <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=EDIT&login=<?php echo $lista["login"]?>&IdTrabajo=<?php echo $lista["IdTrabajo"]?>"><input type="image" src="../Views/images/edit.png" name="action" title="<?php echo $strings['Editar'] ?>" value="EDIT"></a>
                    <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=DELETE&login=<?php echo $lista["login"]?>&IdTrabajo=<?php echo $lista["IdTrabajo"]?>"><input type="image" src="../Views/images/delete.png" name="action" title="<?php echo $strings['Eliminar'] ?>" value="DELETE"></a></tr>
        </table>

        <div class="accionesTable">
            <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=SHOWALL"><input type="image" name="action" value="SHOWALL" src="../Views/images/back.png" title="<?php echo $strings['Volver'] ?>"></a>
        </div>
    </section>	
<?php
  include '../Views/Footer.php';
}

}
?>