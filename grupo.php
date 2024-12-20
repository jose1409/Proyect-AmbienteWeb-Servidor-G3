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
    <?php include 'menu.php';

    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }

    ?>

    <!-- main -->
    <main class="main-content">
        <div class="content-wrapper">
            <div class="top-bar">
                <h4 class="mb-0">Gestión de Grupos</h4>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#grupoModal">
                        <i class="fas fa-plus"></i> Nuevo Grupo
                    </button>
                </div>
            </div>


            <!-- tabla de grupos -->
            <div class="table-container">
                <div class="table-responsive">


                    <!-- filtro -->
                    <form class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-1">
                                <input type="text" class="form-control" placeholder="ID" id="filter-id">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Nombre del Grupo" id="filter-nombre-grupo">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Institución" id="filter-institucion">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary w-100" id="apply-filters">Filtrar</button>
                            </div>
                        </div>
                    </form>


                    <table class="table custom-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Grupo</th>
                                <th>Institución</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once './Controller/GrupoController.php';
                            require_once './Controller/InstitucionController.php';

                            try {
                                // Instanciar controladores
                                $grupoController = new GrupoController();
                                $institucionController = new InstitucionController();

                                $grupos = $grupoController->listar();

                                if (!empty($grupos)) {
                                    foreach ($grupos as $grupo) {
                                        $id_grupo = htmlspecialchars($grupo->getIdGrupo());
                                        $nombre_grupo = htmlspecialchars($grupo->getNombreGrupo());
                                        $id_institucion = htmlspecialchars($grupo->getIdInstitucion());

                                        // Obtener el nombre de la institución
                                        $institucion = $institucionController->obtenerPorId($id_institucion);
                                        $nombre_institucion = $institucion ? htmlspecialchars($institucion->getNombre()) : 'No encontrado';

                                        echo <<<HTML
                                        <tr>
                                            <td>{$id_grupo}</td>
                                            <td>{$nombre_grupo}</td>
                                            <td>{$nombre_institucion}</td>
                                            <td>
                                                <!-- Botón Editar -->
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{$id_grupo}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{$id_grupo}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Editar -->
                                        <div class="modal fade" id="editModal{$id_grupo}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Grupo</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="./Acciones/Grupos/editar.php?action=editar" method="POST">
                                                            <input type="hidden" name="id_grupo" value="{$id_grupo}">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nombre del Grupo</label>
                                                                <input type="text" class="form-control" name="nombre_grupo" value="{$nombre_grupo}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Institución</label>
                                                                <select class="form-select" name="id_institucion" required>
                                                                    <option value="">Seleccione una institución</option>
HTML;
                                        foreach ($institucionController->listar() as $institucion) {
                                            $selected = $institucion->getIdInstitucion() == $id_institucion ? 'selected' : '';
                                            echo "<option value=\"{$institucion->getIdInstitucion()}\" $selected>{$institucion->getNombre()}</option>";
                                        }
                                        echo <<<HTML
                                                                </select>
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
                                        <div class="modal fade" id="deleteModal{$id_grupo}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Eliminar Grupo</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de que deseas eliminar el grupo "<strong>{$nombre_grupo}</strong>"?</p>
                                                        <form action="./Acciones/Grupos/eliminar.php?action=eliminar" method="POST">
                                                            <input type="hidden" name="id_grupo" value="{$id_grupo}">
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
                                    echo "<tr><td colspan='3'>No se encontraron registros en la tabla <code>grupo</code>.</td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='3'>Error al consultar la base de datos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
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

    <!-- Modal para agregar grupo -->
    <div class="modal fade" id="grupoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Grupo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="./Acciones/Grupos/agregar.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Grupo</label>
                            <input type="text" class="form-control" name="nombre_grupo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Institución</label>
                            <select class="form-select" name="id_institucion" required>
                                <option value="">Seleccione una institución</option>
                                <?php
                                $instituciones = $institucionController->listar();
                                foreach ($instituciones as $institucion) {
                                    echo "<option value=\"{$institucion->getIdInstitucion()}\">{$institucion->getNombre()}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('apply-filters').addEventListener('click', function() {
            const idFilter = document.getElementById('filter-id').value.toLowerCase();
            const nombreFilter = document.getElementById('filter-nombre-grupo').value.toLowerCase();
            const institucionFilter = document.getElementById('filter-institucion').value.toLowerCase();

            const rows = document.querySelectorAll('.custom-table tbody tr');
            rows.forEach(row => {
                const id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const nombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const institucion = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                const match =
                    (idFilter === "" || id.includes(idFilter)) &&
                    (nombreFilter === "" || nombre.includes(nombreFilter)) &&
                    (institucionFilter === "" || institucion.includes(institucionFilter));

                row.style.display = match ? '' : 'none';
            });
        });
    </script>



</body>



</html>