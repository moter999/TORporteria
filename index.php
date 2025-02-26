<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Choferes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f0f8ff; /* Color de fondo */
            background-image: url('https://example.com/your-image.jpg'); /* URL de la imagen de fondo */
            background-size: cover;
            background-position: center;
        }
        .container-fluid {
            height: 100%;
            padding: 20px;
            animation: fadeIn 1.5s;
        }
        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }
        .btn-primary, .btn-secondary, .btn-warning, .btn-danger {
            transition: transform 0.2s;
        }
        .btn-primary:hover, .btn-secondary:hover, .btn-warning:hover, .btn-danger:hover {
            transform: scale(1.05);
        }
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container-fluid h-100">
        <h2 class="text-center my-4 animate__animated animate__bounceInDown">Gestión de Choferes</h2>
        <a href="../login.php" class="btn btn-secondary mb-3 animate__animated animate__fadeInLeft">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
        <button type="button" class="btn btn-primary mb-3 animate__animated animate__fadeInRight" data-toggle="modal" data-target="#createModal">
            <i class="fas fa-plus"></i> Agregar Chofer
        </button>
        <div class="input-group mb-3">
            <input type="text" id="searchInput" class="form-control animate__animated animate__fadeInUp" placeholder="Buscar registros...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="loadRecords()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="table-responsive animate__animated animate__fadeInUp">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Chofer</th>
                        <th>Cod1</th>
                        <th>Patente</th>
                        <th>F.Salida</th>
                        <th>F.Ingreso</th>
                        <th>H.Sal</th>
                        <th>H.Ing</th>
                        <th>T.Ocupado</th>
                        <th>Cod2</th>
                        <th>Lugar</th>
                        <th>Detalle</th>
                        <th>K.Ing</th>
                        <th>K.Sal</th>
                        <th>K.Ocup</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="choferTableBody">
                    <!-- Los registros se cargarán aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Crear -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Agregar Chofer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="chofer">Chofer</label>
                                    <input type="text" class="form-control" id="chofer" name="chofer" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cod1">Cod1</label>
                                    <input type="text" class="form-control" id="cod1" name="cod1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="patente">Patente</label>
                                    <input type="text" class="form-control" id="patente" name="patente" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="f_salida">F.Salida</label>
                                    <input type="date" class="form-control" id="f_salida" name="F.Salida">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="f_ingreso">F.Ingreso</label>
                                    <input type="date" class="form-control" id="f_ingreso" name="F.Ingreso" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="h_sal">H.Sal</label>
                                    <input type="time" class="form-control" id="h_sal" name="H.Sal">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="h_ing">H.Ing</label>
                                    <input type="time" class="form-control" id="h_ing" name="H.Ing">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="t_ocupado">T.Ocupado</label>
                                    <input type="text" class="form-control" id="t_ocupado" name="T.Ocupado">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cod2">Cod2</label>
                                    <input type="text" class="form-control" id="cod2" name="Cod2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lugar">Lugar</label>
                                    <input type="text" class="form-control" id="lugar" name="Lugar">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="detalle">Detalle</label>
                                    <input type="text" class="form-control" id="detalle" name="Detalle">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="k_ing">K.Ing</label>
                                    <input type="number" class="form-control" id="k_ing" name="K.Ing">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="k_sal">K.Sal</label>
                                    <input type="number" class="form-control" id="k_sal" name="K.Sal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="k_ocup">K.Ocup</label>
                                    <input type="number" class="form-control" id="k_ocup" name="K.Ocup">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Chofer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editChofer">Chofer</label>
                                    <input type="text" class="form-control" id="editChofer" name="chofer" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editCod1">Cod1</label>
                                    <input type="text" class="form-control" id="editCod1" name="cod1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editPatente">Patente</label>
                                    <input type="text" class="form-control" id="editPatente" name="patente" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editF_Salida">F.Salida</label>
                                    <input type="date" class="form-control" id="editF_Salida" name="F.Salida">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editF_Ingreso">F.Ingreso</label>
                                    <input type="date" class="form-control" id="editF_Ingreso" name="F.Ingreso" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editH_Sal">H.Sal</label>
                                    <input type="time" class="form-control" id="editH_Sal" name="H.Sal">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editH_Ing">H.Ing</label>
                                    <input type="time" class="form-control" id="editH_Ing" name="H.Ing">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editT_Ocupado">T.Ocupado</label>
                                    <input type="text" class="form-control" id="editT_Ocupado" name="T.Ocupado">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editCod2">Cod2</label>
                                    <input type="text" class="form-control" id="editCod2" name="Cod2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editLugar">Lugar</label>
                                    <input type="text" class="form-control" id="editLugar" name="Lugar">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editDetalle">Detalle</label>
                                    <input type="text" class="form-control" id="editDetalle" name="Detalle">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editK_Ing">K.Ing</label>
                                    <input type="number" class="form-control" id="editK_Ing" name="K.Ing">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editK_Sal">K.Sal</label>
                                    <input type="number" class="form-control" id="editK_Sal" name="K.Sal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editK_Ocup">K.Ocup</label>
                                    <input type="number" class="form-control" id="editK_Ocup" name="K.Ocup">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Cargar registros al cargar la página
            loadRecords();

            // Manejar el envío del formulario de creación
            $('#createForm').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'create.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        showAlert('success', 'Registro creado exitosamente');
                        $('#createModal').modal('hide');
                        loadRecords();
                    },
                    error: function() {
                        showAlert('danger', 'Error al crear el registro');
                    }
                });
            });

            // Manejar el envío del formulario de edición
            $('#editForm').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'update.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        showAlert('success', 'Registro actualizado exitosamente');
                        $('#editModal').modal('hide');
                        loadRecords();
                    },
                    error: function() {
                        showAlert('danger', 'Error al actualizar el registro');
                    }
                });
            });

            // Manejar el evento de búsqueda
            $('#searchInput').on('input', function() {
                loadRecords();
            });
        });

        function loadRecords() {
            var searchQuery = $('#searchInput').val();
            $.ajax({
                url: 'read.php',
                method: 'GET',
                data: { search: searchQuery },
                success: function(data) {
                    $('#choferTableBody').html(data);
                }
            });
        }

        function editRecord(id) {
            $.ajax({
                url: 'get_record.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    $('#editId').val(data.id);
                    $('#editChofer').val(data.Chofer);
                    $('#editCod1').val(data.cod1);
                    $('#editPatente').val(data.Patente);
                    $('#editF_Salida').val(data['F.Salida'].replace(' ', 'T'));
                    $('#editF_Ingreso').val(data['F.Ingreso'].replace(' ', 'T'));
                    $('#editH_Sal').val(data['H.Sal']);
                    $('#editH_Ing').val(data['H.Ing']);
                    $('#editT_Ocupado').val(data['T.Ocupado']);
                    $('#editCod2').val(data.Cod2);
                    $('#editLugar').val(data.Lugar);
                    $('#editDetalle').val(data.Detalle);
                    $('#editK_Ing').val(data['K.Ing']);
                    $('#editK_Sal').val(data['K.Sal']);
                    $('#editK_Ocup').val(data['K.Ocup']);
                    $('#editModal').modal('show');
                },
                error: function() {
                    showAlert('danger', 'Error al obtener el registro');
                }
            });
        }

        function deleteRecord(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                $.ajax({
                    url: 'delete.php',
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        showAlert('success', 'Registro eliminado exitosamente');
                        loadRecords();
                    },
                    error: function() {
                        showAlert('danger', 'Error al eliminar el registro');
                    }
                });
            }
        }

        function showAlert(type, message) {
            var alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                              </div>`;
            $('.container-fluid').prepend(alertHtml);
            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        }
    </script>
</body>
</html>
