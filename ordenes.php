<?php
session_start();
include "funciones_ordenes.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Gestión de Talleres - Órdenes de Trabajo</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    .sidebar {
      background-color: #f8f9fa;
      padding: 20px;
    }

    .content {
      padding: 20px;
    }

    .order-item {
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      background-color: #ffffff;
    }

    .order-actions {
      margin-top: 10px;
    }

    .footer {
      background-color: #f8f9fa;
      padding: 20px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 sidebar">
        <h3>Sistema de Gestión de Talleres</h3>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
          <li class="nav-item"><a class="nav-link" href="vehiculos.php">Vehículos</a></li>
          <li class="nav-item"><a class="nav-link" href="ordenes.php">Órdenes de Trabajo</a></li>
          <li class="nav-item"><a class="nav-link" href="gastos.php">Gastos</a></li>
          <li class="nav-item"><a class="nav-link" href="pagos.php">Registro de Pagos</a></li>
          <li class="nav-item"><a class="nav-link" href="cuentas.php">Cuentas</a></li>
        </ul>
      </div>

      <div class="col-md-9 content">
        <h2>Órdenes de Trabajo</h2>
        <!-- Campos del formulario para agregar orden de trabajo -->
        <div class="row">
          <div class="col-md-6">
            <h3>Agregar Órden de Trabajo</h3>
            <form action="funciones_ordenes.php" method="POST">
              <input type="hidden" name="action" value="add">
              <div class="form-group">
                <label for="patente">Patente del Vehículo:</label>
                <select class="form-control" id="patente" name="patente" required>
                  <?php
                  $vehiculos = obtenerVehiculos();

                  if (count($vehiculos) > 0) {
                    foreach ($vehiculos as $vehiculo) {
                      $patente = $vehiculo['Patente'];
                      echo "<option value=\"$patente\">$patente</option>";
                    }
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
              </div>
              <button type="submit" class="btn btn-primary">Agregar Orden</button>
            </form>
          </div>
        </div>

        <hr>

        <h3>Listado de Órdenes de Trabajo</h3>
  <?php
  // Obtener la lista de órdenes de trabajo
  $ordenes = obtenerOrdenesTrabajo();

  if (count($ordenes) > 0) {
    foreach ($ordenes as $orden) {
      $ordenID = $orden['ID'];
      $clienteNombre = $orden['ClienteNombre'];
      $vehiculoPatente = $orden['VehiculoPatente'];
      $descripcion = $orden['Descripcion'];
      $fechaInicio = $orden['FechaInicio'];
      $estado = $orden['Estado'];
      $total = $orden['Total']; // Nuevo campo "Total"
      ?>
      <div class="order-item">
        <h5>Orden de Trabajo <?php echo $ordenID; ?></h5>
        <p><strong>Cliente:</strong> <?php echo $clienteNombre; ?></p>
        <p><strong>Vehículo:</strong> <?php echo $vehiculoPatente; ?></p>
        <p><strong>Descripción:</strong> <?php echo $descripcion; ?></p>
        <p><strong>Fecha de Inicio:</strong> <?php echo $fechaInicio; ?></p>
        <p><strong>Estado:</strong> <?php echo $estado; ?></p>
        <!-- Mostrar el campo "Total" -->
        <p><strong>Total:</strong> <?php echo $total; ?></p>
        <div class="order-actions">
          <?php if ($estado === 'abierto') { ?>
            <form action="funciones_ordenes.php" method="POST">
              <input type="hidden" name="action" value="close">
              <input type="hidden" name="orden_id" value="<?php echo $ordenID; ?>">
              <button type="submit" class="btn btn-danger">Cerrar Orden</button>
            </form>
          <?php } ?>

          <!-- Botón para abrir el modal de modificación -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalModificar<?php echo $ordenID; ?>">
            Modificar
          </button>

          <!-- Modal de modificación -->
          <div class="modal fade" id="modalModificar<?php echo $ordenID; ?>" tabindex="-1" role="dialog" aria-labelledby="modalModificarLabel<?php echo $ordenID; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalModificarLabel<?php echo $ordenID; ?>">Modificar Orden de Trabajo <?php echo $ordenID; ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="funciones_ordenes.php" method="POST">
                  <div class="modal-body">
                    <input type="hidden" name="action" value="modify">
                    <input type="hidden" name="orden_id" value="<?php echo $ordenID; ?>">
                    <div class="form-group">
                      <label for="cliente">Cliente:</label>
                      <select class="form-control" id="cliente" name="cliente" required>
                        <?php
                        $clientes = obtenerClientes();

                        foreach ($clientes as $cliente) {
                          $clienteID = $cliente['ID'];
                          $clienteNombre = $cliente['Nombre'];
                          echo "<option value=\"$clienteID\">$clienteNombre</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="vehiculo">Vehículo:</label>
                      <select class="form-control" id="vehiculo" name="vehiculo" required>
                        <?php
                        foreach ($vehiculos as $vehiculo) {
                          $vehiculoID = $vehiculo['ID'];
                          $vehiculoPatente = $vehiculo['Patente'];
                          echo "<option value=\"$vehiculoID\">$vehiculoPatente</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="descripcion">Descripción:</label>
                      <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
  } else {
    echo "<p>No hay órdenes de trabajo registradas.</p>";
  }
  ?>
</div>

    <div class="row">
      <div class="col-md-12 footer">
        <p>Sistema de Gestión de Talleres - <?php echo date("Y"); ?></p>
      </div>
    </div>
  </div>

  <!-- Scripts de Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
