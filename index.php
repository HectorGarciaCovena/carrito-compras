<?php
include 'Configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Carrito de Compras</title>
    <meta charset="utf-8">
    
    <!-- Enlazamos la web con la versión 3.3.7 de Bootstrap y sus dependencias (jQuery) a través de BootstrapCDN 
    y Google CDN.-->
    <!-- Aplicamos los estilos predefinidos de Bootstrap a la página.-->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Enlazamos la página con la versión 1.12.4 de jQuery alojada en los servidores de Google.-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Enlazamos la página con el archivo JavaScript de Bootstrap.-->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container {
            padding: 10px;
        }

        .cart-link {
            width: 100%;
            text-align: right;
            display: block;
            font-size: 22px;
        }
    </style>
</head>
</head>

<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">

                <!-- MENU -->
                <ul class="nav nav-pills">
                    <li role="presentacion" class="active"><a href="index.php">Inicio</a></li>
                    <li role="presentacion"><a href="VerCarta.php">Carrito de Compras</a></li>
                    <li role="presentacion"><a href="Pagos.php">Pagar</a></li>
                    <li role="presentacion"><a href="https://www.facebook.com/hectormiguel.garciacovena.9" target="_blank">Héctor García</a></li>
                </ul>
            </div>

            <!-- CATÁLOGO DE PRODUCTOS -->
            <div class="panel-body">
                <h1>Tienda de Relojes</h1>
                <a href="VerCarta.php" class="cart-link" title="Ver Carta"><i class="glyphicon glyphicon-shopping-cart"></i></a>
                <div id="products" class="row list-group">
                    <?php
                    
                    //Extraemos los productos tabla "mis_productos" de la Base de Datos
                    $query = $db->query("SELECT * FROM mis_productos ORDER BY id DESC LIMIT 10");
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                    ?>
                            <!-- Mostramos cada producto en un contenedor de tipo "thumbnail"... -->
                            <!-- ... con su respectivo detalle (imagen, nombre y precio) -->
                            <!-- ... y almacena la información extraída en el array asociativo $row -->
                            <div class="item col-lg-4">
                                <div class="thumbnail">
                                    <img src="<?php echo $row["image"]; ?>" alt="<?php echo $row["name"]; ?>" class="img-responsive">
                                    <div class="caption">
                                        <h4 class="list-group-item-heading"><?php echo $row["name"]; ?></h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="lead"><?php echo '$' . $row["price"]; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-success" href="AccionCarta.php?action=addToCart&id=<?php echo $row["id"]; ?>">Enviar al Carrito</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>Producto(s) no existe.....</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="panel-footer"style= text-align: center>&copy; Héctor García, 2023 - Todos los derechos reservados</div>
        

    </div>
</body>

</html>