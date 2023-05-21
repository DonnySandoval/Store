<?php 
    session_start(); 
    error_reporting(E_PARSE);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pedido</title>
    <?php include './inc/link.php'; ?>
</head>
<body id="container-page-index">
    <?php include './inc/navbar.php'; ?>
    <section id="container-pedido">
        <div class="container">
            <div class="page-header">
              <h1>PEDIDOS <small class="tittles-pages-logo">STORE</small></h1>
            </div>
            <br><br><br>
            <div class="row">
              <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <?php
                  require_once "library/configServer.php";
                  require_once "library/consulSQL.php";
                  if($_SESSION['UserType']=="Admin" || $_SESSION['UserType']=="User"){
                    if(isset($_SESSION['carro'])){
                ?>
                      <br><br><br>
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-xs-10 col-xs-offset-1">
                            <p class="text-center lead">Datos de envio y pago</p>
                            <img class="img-responsive center-all-contens" src="assets/img/credit-card.png">
                            <p class="text-center">
                              <button class="btn btn-lg btn-raised btn-success btn-block" data-toggle="modal" data-target="#PagoModalTran">Transaccion Bancaria</button>
                            </p>
                          </div>
                        </div>
                      </div>
                <?php
                    }else{
                      echo '<p class="text-center lead">No tienes pedidos pendientes de pago</p>';
                    }
                  }else{
                    echo '<p class="text-center lead">Inicia sesión para realizar pedidos</p>';
                  }
                ?>
              </div>
            </div>
        </div>
        <?php
            if($_SESSION['UserType']=="User"){
                $consultaC=ejecutarSQL::consultar("SELECT * FROM venta WHERE NIT='".$_SESSION['UserNIT']."'");
        ?>
            <div class="container" style="margin-top: 70px;">
              <div class="page-header">
                <h1>Mis pedidos</h1>
              </div>
            </div>
        <?php
            if(mysqli_num_rows($consultaC)>=1){
        ?> 
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Envío</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($rw=mysqli_fetch_array($consultaC, MYSQLI_ASSOC)){
                                    ?> 
                                        <tr>
                                            <td><?php echo $rw['Fecha']; ?></td>
                                            <td>$<?php echo $rw['TotalPagar']; ?></td>
                                            <td>
                                            <?php
                                              switch ($rw['Estado']) {
                                                case 'Enviado':
                                                  echo '<span style="color: blue;">En camino</span>';
                                                  break;
                                                case 'Pendiente':
                                                  echo '<span style="color: orange;">En espera</span>';
                                                  break;
                                                case 'Entregado':
                                                  echo '<span style="color: green;">Entregado</span>';
                                                  break;
                                                case 'Cancelado':
                                                  echo '<span style="color: red;">Cancelado</span>';
                                                  break;
                                                default:
                                                  echo '<span style="color: red;">Sin información</span>';
                                                  break;
                                              }
                                            ?>
                                            </td>
                                            <td><?php echo $rw['TipoEnvio']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        <?php
            }else{
              echo '<p class="text-center lead">No tienes ningun pedido realizado</p>';
            }
            mysqli_free_result($consultaC);
        }
        ?>
    </section>
    <div class="modal fade" id="PagoModalTran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <form class="modal-content FormCatElec" action="process/confirmcompra.php" method="POST" role="form" data-form="save">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Datos de envio y pago</h4>
          </div>
          <div class="modal-body">
            <?php
              $consult1=ejecutarSQL::consultar("SELECT * FROM cuentabanco");
              if(mysqli_num_rows($consult1)>=1){
                $datBank=mysqli_fetch_array($consult1, MYSQLI_ASSOC);
            ?>
            <p class="text-warning">Nota:</p>
            <p>~Proporciona un numero correcto de telefono, se te llamara para confirmar el pedido."</p>
            <p>~Si eres de sahsa deje vacia la opcion "Seleccione la imagen del comprobante..."</p>
            <p>~Si eres otro lado realizar el pago por medio de billetera movil y mandar el comprobante."</p>
            <br>
            <p>Datos de la Billera</p>
            <p>
              <strong>Numero de billera:</strong> 88888888
            </p>
                <?php if($_SESSION['UserType']=="Admin"): ?>
                <div class="form-group">
                    <label>Dirección de envio</label>
                    <input class="form-control" type="text" name="NumDepo" placeholder="Escriba el lugar donde quiere que le envien el producto." maxlength="100" required="">
                </div>
                <div class="form-group">
                  <span>Tipo De Envio</span>
                  <select class="form-control" name="tipo-envio" data-toggle="tooltip" data-placement="top" title="Elige El Tipo De Envio">
                      <option value="" disabled="" selected="">Selecciona una opción</option>
                      <option value="Recoger Por Tienda">Recoger en Tienda</option>
                      <option value="Envio Por Currier">Envio a casa "10C$ por envio"</option> 
                  </select>
               </div>
                <div class="form-group">
                    <label>DNI del cliente</label>
                    <input class="form-control" type="text" name="Cedclien" placeholder="DNI del cliente" maxlength="15" required="">
                </div>
                <div class="form-group">
                      <input type="file" name="comprobante">
                      <div class="input-group">
                        <input type="text" readonly="" class="form-control" placeholder="Seleccione la imagen del comprobante...">
                          <span class="input-group-btn input-group-sm">
                            <button type="button" class="btn btn-fab btn-fab-mini">
                              <i class="fa fa-file-image-o" aria-hidden="true"></i>
                            </button>
                          </span>
                      </div>
                        <p class="help-block"><small>Tipos de archivos admitidos, imagenes .jpg y .png. Maximo 5 MB</small></p>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label>Dirección de envio</label>
                        <input class="form-control" type="text" name="NumDepo" placeholder="Escriba el lugar donde quiere que le envien el producto." maxlength="100" required="">
                    </div>
                    <div class="form-group">
                        <label>Numero de telefono</label>
                        <input class="form-control" type="text" name="NumeroTelefono" placeholder="Escribe el numero de telefono." maxlength="8" required="">
                    </div>
                    <div class="form-group">
                      <span>Tipo De Envio</span>
                      <select class="form-control" name="tipo-envio" data-toggle="tooltip" data-placement="top" title="Elige El Tipo De Envio">
                          <option value="" disabled="" selected="">Selecciona una opción</option>
                          <option value="Recoger Por Tienda">Recoger en Tienda</option>
                          <option value="Envio Por Currier">Envio a casa "10C$ por envio"</option> 
                      </select>
                   </div>
                    <input type="hidden" name="Cedclien" value="<?php echo $_SESSION['UserNIT']; ?>">
                    <div class="form-group">
                      <input type="file" name="comprobante">
                      <div class="input-group">
                        <input type="text" readonly="" class="form-control" placeholder="Seleccione la imagen del comprobante...">
                          <span class="input-group-btn input-group-sm">
                            <button type="button" class="btn btn-fab btn-fab-mini">
                              <i class="fa fa-file-image-o" aria-hidden="true"></i>
                            </button>
                          </span>
                      </div>
                        <p class="help-block"><small>Tipos de archivos admitidos, imagenes .jpg y .png. Maximo 5 MB</small></p>
                    </div>
                <?php 
                endif;
              }else{
                echo "Ocurrio un error: No ha ingresado los datos de envio";
              }
              mysqli_free_result($consult1);
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm btn-raised" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary btn-sm btn-raised">Confirmar</button>
          </div>
        </form>
      </div>
    </div>
    <div class="ResForm"></div>
    <?php include './inc/footer.php'; ?>
</body>
</html>
