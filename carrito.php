<?php 
    session_start(); 
    error_reporting(E_PARSE);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Carrito de compras</title>
    <?php include './inc/link.php'; ?>
    <style>
        body {
            background-color: #f8f8f8;
        }
        #container-pedido {
            padding-top: 100px;
            padding-bottom: 100px;
        }
        

        .page-header {
            text-align: center;
        }
        .table {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
        .bg-success {
            background-color: #5cb85c;
            color: #fff;
        }
        .bg-danger {
            background-color: #d9534f;
            color: #fff;
        }
        .btn {
            border-radius: 20px;
        }
        .btn-lg {
            font-size: 18px;
            padding: 12px 30px;
        }
        .btn-primary {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #fff;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active {
            background-color: #286090;
            border-color: #204d74;
            color: #fff;
        }
        .btn-primary.btn-outline {
            background-color: transparent;
            border-color: #337ab7;
            color: #337ab7;
        }
        .btn-primary.btn-outline:hover, .btn-primary.btn-outline:focus, .btn-primary.btn-outline:active, .btn-primary.btn-outline.active {
            background-color: #337ab7;
            color: #fff;
        }
        .btn-success {
            background-color: #5cb85c;
            border-color: #4cae4c;
            color: #fff;
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active, .btn-success.active {
            background-color: #449d44;
            border-color: #398439;
            color: #fff;
        }
        .btn-success.btn-outline {
            background-color: transparent;
            border-color: #5cb85c;
            color: #5cb85c;
        }
        .btn-success.btn-outline:hover, .btn-success.btn-outline:focus, .btn-success.btn-outline:active, .btn-success.btn-outline.active {
            background-color: #5cb85c;
            color: #fff;
        }
        .btn-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
            color: #fff;
        }
        .btn-danger:hover, .btn-danger:focus, .btn-danger:active, .btn-danger.active {
            background-color: #c9302c;
            border-color: #ac2925;
            color: #fff;
        }
        .btn-danger.btn-outline {
            background-color: transparent;
            border-color: #d9534f;
            color: #d9534f;
        }
        .btn-danger.btn-outline:hover, .btn-danger.btn-outline:focus, .btn-danger.btn-outline:active, .btn-danger.btn-outline.active {
            background-color: #d9534f;
            color: #fff;
        }
    </style>
</head>
<body id="container-page-index">
    <?php include './inc/navbar.php'; ?>
    <section id="container-pedido">
        <div class="container">
            <div class="page-header">
              <h1>CARRITO DE COMPRAS <small class="tittles-pages-logo">STORE</small></h1>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-xs-12">
                    <?php
                        require_once "library/configServer.php";
                        require_once "library/consulSQL.php";
                        if(!empty($_SESSION['carro'])){
                            $suma = 0;
                            $sumaA = 0;
                            echo '<table class="table table-bordered table-hover"><thead><tr class="bg-success"><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Acciones</th></tr></thead>';
                            foreach($_SESSION['carro'] as $codeProd){
                                $consulta=ejecutarSQL::consultar("SELECT * FROM producto WHERE CodigoProd='".$codeProd['producto']."'");
                                while($fila = mysqli_fetch_array($consulta, MYSQLI_ASSOC)) {
                                    $pref=number_format(($fila['Precio']-($fila['Precio']*($fila['Descuento']/100))), 2, '.', '');
                                    echo "<tbody>
                                            <tr>
                                                <td>".$fila['NombreProd']."</td>
                                                <td>".$pref."</td>
                                                <td>".$codeProd['cantidad']."</td>
                                                <td>".$pref*$codeProd['cantidad']."</td>
                                                <td>
                                                    <form action='process/quitarproducto.php' method='POST' class='FormCatElec' data-form=''>
                                                        <input type='hidden' value='".$codeProd['producto']."' name='codigo'>
                                                        <button  class='btn  btn-xs btn-text bt' >Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>";
                                    $suma += $pref*$codeProd['cantidad'];
                                    $sumaA += $codeProd['cantidad'];
                                }
                                mysqli_free_result($consulta);
                            }
                            echo '<tfoot>
                                    <tr class="bg-danger">
                                        <td colspan="2">Total</td>
                                        <td><strong>'.$sumaA.'</strong></td>
                                        <td><strong>$'.number_format($suma,2).'</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="ResForm"></div>';
                            echo '<div class="text-center">
                            <a href="product.php" class="btn btn-primary btn-lg" style="color: #fff;">Seguir comprando</a>
                            <a href="process/vaciarcarrito.php" class="btn btn-success btn-lg" style="color: #fff;">Vaciar el carrito</a>
                            <a href="pedido.php" class="btn btn-danger btn-lg" style="color: #fff;">Confirmar el pedido</a>
                        </div>';
                        }else{
                            echo '<p class="text-center text-danger lead">El carrito de compras está vacío</p><br>
                            <div class="text-center">
                                <a href="product.php" class="btn btn-success btn-lg" style="color: #fff;">Ir a Productos</a>
                            </div>';
                                                        
                        }  
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php include './inc/footer.php'; ?>
</body>
</html>
