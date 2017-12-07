<?php
/*
//Clase : NOTA_TRABAJO_SEARCH
//Creado el : 29-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Muestra el formulario de búsqueda de notas por todos sus campos

*/
class NOTA_TRABAJO_SEARCH{
function __construct(){
    $this->render();
}

function render(){

  include '../Views/Header.php';

?>

    <script type="text/javascript"> 
        <?php include '../Views/js/validacionesNOTA_TRABAJO.js' ?>
    </script>

    <section class="pagina" style="min-height: 900px" >
        <fieldset class="search">
                <legend style="margin-left: 30%"><?php echo $strings['Buscar Nota']?></legend>
            <form method="post" name="SEARCH" action="../Controllers/NOTA_TRABAJO_Controller.php">
                <div id="izquierda">
                    <label for="login"><?php echo $strings['Login'] ?>: </label>
                        <input type="text" name="login" maxlength="9" size="9" onblur="javascript:void(validarLoginBuscar(this, 9))" ><div id="login" class="oculto" style="display:none"><?php echo $strings['div_Alfanumerico']?></div> <div id="loginVacio" class="oculto" style="display:none"><?php echo $strings['div_vacio']?></div> 
                </div>

                <div id="izquierda">
                    <label for="IdTrabajo"><?php echo $strings['IdTrabajo']?>: </label>
                        <input type="text" name="IdTrabajo" maxlength="6" size="6"  onblur="javascript:void(validarIdTrabajoBuscar(this, 6))" ><div id="IdTrabajo" class="oculto" style="display:none"><?php echo $strings['div_Alfanumerico']?></div> <div id="IdTrabajoVacio" class="oculto" style="display:none"><?php echo $strings['div_vacio']?></div> 
                </div>

                <div id="izquierda">
                    <label for="NotaTrabajo"><?php echo $strings['Nota Trabajo']?>: </label>
                        <input type="text" name="NotaTrabajo" maxlength="4" size="4"  onblur="" ><div id="NotaTrabajo" class="oculto" style="display:none"><?php echo $strings['div_Alfanumerico']?></div> <div id="NotaTrabajoVacio" class="oculto" style="display:none"><?php echo $strings['div_vacio']?></div> 
                </div>
        
                <div class="acciones" style="float: right; margin-left:0%; margin-right: 50%">
                     <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=SEARCH"><input type="image" name="action" value="SEARCH" action="#" src="../Views/images/search.png" title="<?php echo $strings['Buscar']?>" onclick="return validar('SEARCH')"></a>
                </div>
            </form>  
            <div class="acciones" style="float: left;">
                <a href="../Controllers/NOTA_TRABAJO_Controller.php?action=SHOWALL"><input type="image" src="../Views/images/back.png" title="<?php echo $strings['Volver']?>"></a>
            </div>
        </fieldset>
    </section>
<?php
  include '../Views/Footer.php';
}

}
?>