<?php

/*
//Clase : GRUPO_ADD
//Creado el : 24-11-2017
//Creado por: SOLFAMIDAS
//-------------------------------------------------------

Muestra el formulario de registro de grupo que permite al administrador añadir un grupo al sistema

*/
class GRUPO_ADD {
   function __construct(){
    $this->render();
   }

//funcion que muestra los datos al usuario

function render(){

include '../Views/Header.php';

?>

<script type="text/javascript">
    
    <?php include '../Views/js/validacionesGRUPO.js' ?>
</script>
     <section class="pagina" style="min-height: 900px">
         <fieldset class="add">
                <legend style="margin-left: 30%"><?php echo $strings['AñadirGrupo'] ?></legend>
            <form method="post" name="ADD"  action="../Controllers/GRUPO_Controller.php" >
                <div id="izquierda">
                    <label for="IdGrupo"><?php echo $strings['IdGrupo']?>: </label>
                        <input type="text" name="IdGrupo" maxlength="6" size="6" onblur="javascript:void(validarIdGrupo(this, 6))" ><div id="IdGrupo" class="oculto" style="display:none"><?php echo $strings['div_Alfanumerico']?></div> <div id="IdGrupoVacio" class="oculto" style="display:none"><?php echo $strings['div_vacio']?></div> 
                </div>

                <div id="izquierda">
                    <label for="NombreGrupo"><?php echo $strings['NombreGrupo']?>: </label>
                        <input type="text" name="NombreGrupo" maxlength="60" size="60" onblur="javascript:void(validarNombreGrupo(this, 60))" ><div id="NombreGrupo" class="oculto" style="display:none"><?php echo $strings['div_Alfanumerico']?></div> <div id="NombreGrupoVacio" class="oculto" style="display:none"><?php echo $strings['div_vacio']?></div> 
                </div>

                <div id="izquierda">
                    <label for="DescripGrupo"><?php echo $strings['DescripGrupo']?>: </label>
                        <input type="text" name="DescripGrupo" maxlength="100" size="100" onblur="javascript:void(validarDescripGrupo(this, 100))"  ><div id="DescripGrupo" class="oculto" style="display:none"><?php echo $strings['div_Alfanumerico']?></div> <div id="DescripGrupoVacio" class="oculto" style="display:none"><?php echo $strings['div_vacio'] ?></div>
                </div>

                <div class="acciones" style="float: right; margin-left:0%; margin-right: 50%">
                    <a href="../Controllers/GRUPO_Controller.php?action=ADD"> <input type="image" name="action" value="ADD" src="../Views/images/confirmar.png" title="<?php echo $strings['Enviar Formulario'] ?>" onclick="return validar('ADD')"></a>
                </div>
             </form>                     
                <div class="acciones" style="float: left;">
                     <a href="../Controllers/GRUPO_Controller.php?action=SHOWALL"><input type="image" src="../Views/images/back.png" title="<?php echo $strings['Volver']?>"></a>
                </div>
         </fieldset> 
    </section>
<?php
        include '../Views/Footer.php';
    }

}

?>
