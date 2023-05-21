<?php
session_start();
require_once "library/configServer.php";
require_once "library/consulSQL.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['CodigoProd'])) {
    $codigoProd = $_GET['CodigoProd'];
    $consulta = ejecutarSQL::consultar("SELECT * FROM producto WHERE CodigoProd='$codigoProd' AND Stock > 0 AND Estado='Activo'");
    $fila = mysqli_fetch_assoc($consulta);

    if ($fila) {
        $producto = array(
            'producto' => $codigoProd,
            'cantidad' => 1
        );

        if (empty($_SESSION['carro'])) {
            $_SESSION['carro'][] = $producto;
        } else {
            $found = false;
            foreach ($_SESSION['carro'] as &$prod) {
                if ($prod['producto'] === $codigoProd) {
                    $prod['cantidad']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['carro'][] = $producto;
            }
        }

        echo "<script>window.location='product.php';</script>";
    } else {
        echo "<script>alert('El producto no está disponible o no existe');</script>";
        echo "<script>window.location='product.php';</script>";
    }
} else {
    echo "<script>window.location='product.php';</script>";
}
?>
