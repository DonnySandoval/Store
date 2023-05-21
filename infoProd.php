<?php 
    session_start(); 
    error_reporting(E_PARSE);
?>
<?php
include './library/configServer.php';
include './library/consulSQL.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Productos</title>
    <?php include './inc/link.php'; ?>
    <style>
        /* Estilos personalizados */
        .page-header {
            margin-top: 30px;
        }

        #infoproduct {
            padding: 50px 0;
        }

        .product-info {
            margin-bottom: 50px;
        }

        .product-info h3 {
            text-align: center;
        }

        .product-info h4 {
            margin: 10px 0;
            font-weight: bold;
        }

        .product-info p {
            text-align: center;
            margin-top: 20px;
        }

        .product-image {
            text-align: center;
        }

        .product-image img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .product-buttons {
            margin-top: 20px;
        }

        .product-buttons .btn {
            border-radius: 30px;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 24px;
            }
        }
    </style>
    
</head>

<body id="container-page-product">
    <?php include './inc/navbar.php'; ?>
    <section id="infoproduct">
        <div class="container">
            <div class="row">
                <div class="page-header">
                    <h1>DETALLE DE PRODUCTO <small class="tittles-pages-logo">STORE</small></h1>
                </div>
                <?php
                $CodigoProducto = consultasSQL::clean_string($_GET['CodigoProd']);
                $productoinfo = ejecutarSQL::consultar("SELECT producto.CodigoProd,producto.NombreProd,producto.CodigoCat,categoria.Nombre,producto.Precio,producto.Descuento,producto.Stock,producto.Imagen FROM categoria INNER JOIN producto ON producto.CodigoCat=categoria.CodigoCat  WHERE CodigoProd='" . $CodigoProducto . "'");
                while ($fila = mysqli_fetch_array($productoinfo, MYSQLI_ASSOC)) {
                    echo '<div class="col-xs-12 col-sm-6 product-info">
                        <h3>Información de producto</h3>
                        <h4><strong>Nombre: </strong>' . $fila['NombreProd'] . '</h4>
                        <h4><strong>Precio: </strong>$' . number_format(($fila['Precio'] - ($fila['Precio'] * ($fila['Descuento'] / 100))), 2, '.', '') . '</h4>
                        <h4><strong>Cantidad: </strong>' . $fila['Stock'] . '</h4>
                        <h4><strong>Categoría: </strong>' . $fila['Nombre'] . '</h4>';

                    if ($fila['Stock'] >= 1) {
                        if ($_SESSION['nombreAdmin'] != "" || $_SESSION['nombreUser'] != "") {
                            echo '<form action="process/carrito.php" method="POST" class="FormCatElec" data-form="">
                                <input type="hidden" value="' . $fila['CodigoProd'] . '" name="codigo">
                                <label><small>Agrega la cantidad de productos que añadirás al carrito de compras (Máximo ' . $fila['Stock'] . ' productos)</small></label>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="1" min="1" max="' . $fila['Stock'] . '" name="cantidad">
                                </div>
                                <button class="btn btn-lg btn-success btn-block"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Añadir al carrito</button>
                            </form>
                            <div class="ResForm"></div>';
                        } else {
                            echo '<p><small>Para agregar productos al carrito de compras debes iniciar sesión</small></p>';
                            echo '<button class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target=".modal-login"><i class="fa fa-user"></i>&nbsp;&nbsp; Iniciar sesión</button>';
                        }
                    } else {
                        echo '<p class="text-danger lead">No hay existencias de este producto</p>';
                    }
                    echo '</div>';

                    $imagenFile = ($fila['Imagen'] != "" && is_file("./assets/img-products/" . $fila['Imagen'])) ? "./assets/img-products/" . $fila['Imagen'] : "./assets/img-products/default.png";

                    echo '<div class="col-xs-12 col-sm-6 product-image">
                            <img src="' . $imagenFile . '" alt="Imagen del producto">
                        </div>';
                }
                ?>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="product.php" class="btn btn-lg btn-primary btn-block"><i class="fa fa-mail-reply"></i>&nbsp;&nbsp;Regresar a la tienda</a>
                </div>
            </div>
        </div>
    </section>

    <?php include './inc/footer.php'; ?>

</body>


</html>