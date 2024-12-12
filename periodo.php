<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="View/assets/css/grupo.css">
</head>

<body>
    <?php
    include 'menu.php';
    ?>

    <!-- main -->
    <main class="main-content">
        <div class="content-wrapper">
            <div class="top-bar">
                <h4 class="mb-0">Gestión de periodos</h4>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#grupoModal">
                        <i class="fas fa-plus"></i> Nuevo periodo
                    </button>
                </div>
            </div>

            <!-- search bar-filtro -->
            <div class="search-box mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <label>Nombre</label>
                        <input type="text" class="form-control" placeholder="Buscar por nombre de periodo">
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-6">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" placeholder="Fecha inicial">
                            </div>
                            <div class="col-6">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" placeholder="Fecha final">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label> <!-- Espacio vacío para alinear con los otros elementos -->
                        <button class="btn btn-primary w-100" onclick="searchByFilters()">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>

            <!-- tabla de periodos -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table custom-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Periodo</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once './Controller/PeriodoController.php';

                            try {
                                // Instanciar el controlador
                                $periodoController = new PeriodoController();
                                $periodos = $periodoController->listar();

                                if (!empty($periodos)) {
                                    foreach ($periodos as $periodo) {
                                        $id = htmlspecialchars($periodo->getIdPeriodo());
                                        $nombre = htmlspecialchars($periodo->getNombrePeriodo());
                                        $fechaInicio = htmlspecialchars($periodo->getFechaInicio());
                                        $fechaFin = htmlspecialchars($periodo->getFechaFin());

                                        echo <<<HTML
                            <tr>
                                <td>{$id}</td>
                                <td>{$nombre}</td>
                                <td>{$fechaInicio}</td>
                                <td>{$fechaFin}</td>
                                <td>
                                    <!-- Botón Editar -->
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{$id}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{$id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editModal{$id}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Periodo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="./Acciones/Periodo/editar.php" method="POST">
                                                <input type="hidden" name="action" value="editar">
                                                <input type="hidden" name="id_periodo" value="{$id}">
                                                <div class="mb-3">
                                                    <label class="form-label">Nombre del Periodo</label>
                                                    <input type="text" class="form-control" name="nombre_periodo" value="{$nombre}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio" value="{$fechaInicio}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha Fin</label>
                                                    <input type="date" class="form-control" name="fecha_fin" value="{$fechaFin}" required>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="deleteModal{$id}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Eliminar Periodo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar el periodo "<strong>{$nombre}</strong>"?</p>
                                            <form action="./Acciones/Periodo/eliminar.php" method="POST">
                                                <input type="hidden" name="action" value="eliminar">
                                                <input type="hidden" name="id_periodo" value="{$id}">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
HTML;
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No se encontraron periodos en la base de datos.</td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='5'>Error al consultar la base de datos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <footer class="footer">
            <p>&copy; 2024 Sistema Académico. Todos los derechos reservados.</p>
        </footer>
    </main>

    <!-- Modal Añadir Periodo -->
    <div class="modal fade" id="grupoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Periodo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="periodoForm" action="./Acciones/Periodo/agregar.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label" for="nombre_periodo">Nombre del Periodo</label>
                            <input type="text" class="form-control" name="nombre_periodo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="fecha_fin">Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="periodoForm">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html