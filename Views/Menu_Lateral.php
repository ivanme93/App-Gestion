<!-- 

/*
//Script :Menu_Lateral.php
//Creado el : 16-10-2017
//Creado por: vugsj4
    
    Esta clase es la vista de los usuarios de la BD

*/

-->
<div class="contenedor-menu">    
        <ul class="menu">
            <li><a href="../Controllers/USUARIOS_Controller.php"><i class="icono izquierda fa fa-home"></i><?php echo $strings['Inicio'] ?></a></li>
            <li><a href="#"><i class="icono izquierda fa fa-car" aria-hidden="true"></i> <?php echo $strings['Otros Coches'] ?><i class="icono derecha fa fa-chevron-circle-down" aria-hidden="true"></i></a>
                <ul>
                <li><a href="#">Mercedes C63 AMG</a></li>
                <li><a href="#">Audi RS5 Coupé</a></li>
                <li><a href="#">Lamborghini Aventador SV</a></li>
                <li><a href="#">Ferrari 488 GTB Coupé</a></li>
                </ul>   
            </li>

            <li><a href="#"><i class="icono izquierda fa fa-envelope" aria-hidden="true"></i> <?php echo $strings['Contacto'] ?></a>
            <li><a href="#"><i class="icono izquierda fa fa-cog" aria-hidden="true"></i> <?php echo $strings['Ajustes'] ?></a>
            </li>
        </ul>           
    </div>