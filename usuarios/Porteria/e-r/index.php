<?php
include '../../../conexion/conectado.php';

// Obtener datos ingresados hoy
$hoy = date('Y-m-d');
$sqlHoy = "SELECT * FROM data_chofer WHERE F_Ingreso = '$hoy'";
$resultHoy = $conn->query($sqlHoy);

// Leer todos los datos
$orderBy = "id ASC";
if (isset($_GET['order'])) {
    switch ($_GET['order']) {
        case 'id_asc':
            $orderBy = "id ASC";
            break;
        case 'id_desc':
            $orderBy = "id DESC";
            break;
        case 'mes':
            $orderBy = "MONTH(F_Ingreso) ASC";
            break;
        case 'hora':
            $orderBy = "H_ing ASC";
            break;
    }
}
$sql = "SELECT * FROM data_chofer ORDER BY $orderBy";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion de Choferes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            transition: background-color 0.3s ease;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            transition: width 0.3s ease;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 700;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #34495e;
            transform: translateX(10px);
        }
        .container {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }
        h1, h2 {
            text-align: center;
            color: #2c3e50;
            font-weight: 700;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            table-layout: auto;
            font-size: 14px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: background-color 0.3s ease;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ecf0f1;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px #2980b9;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn:active {
            background-color: #2980b9;
            box-shadow: 0 2px #1c598a;
            transform: translateY(2px);
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .btn-primary {
            background-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-back {
            background-color: #7f8c8d;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-back:hover {
            background-color: #95a5a6;
            transform: scale(1.05);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
            padding: 20px;
        }
        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }
        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
            z-index: 1;
        }
        .close:hover {
            color: #333;
        }
        .form-group {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .form-group input {
            flex: 1;
            min-width: 120px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        .view-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            padding: 15px;
        }
        .view-content p {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin: 0;
            border: 1px solid #e9ecef;
        }
        .view-content p strong {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .message-modal {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .message {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-weight: 500;
            opacity: 0;
            transform: translateY(-10px);
            animation: slideIn 0.3s forwards;
        }
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .message.error {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .message.success {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .message.info {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .message.warning {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .wide-column {
            width: 150px;
        }
        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .form-inline label {
            margin: 0;
        }
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            position: relative;
        }
        .search-container input[type="text"] {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }
        .search-container button {
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .search-container button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .autocomplete-items {
            position: absolute;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        .autocomplete-active {
            background-color: #3498db !important;
            color: #fff;
        }
    </style>
    <script src="modal-functions.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar que las funciones estén disponibles
            console.log('Verificando funciones de modal:', {
                openCreateModal: typeof openCreateModal === 'function',
                openEditModal: typeof openEditModal === 'function',
                openViewModal: typeof openViewModal === 'function'
            });

            // Inicializar autocompletado
            <?php
            $choferes = [];
            $sqlChoferes = "SELECT DISTINCT Chofer FROM data_chofer";
            $resultChoferes = $conn->query($sqlChoferes);
            while($row = $resultChoferes->fetch_assoc()) {
                $choferes[] = $row['Chofer'];
            }
            ?>
            var choferes = <?php echo json_encode($choferes); ?>;
            
            function autocomplete(inp, arr) {
                var currentFocus;
                
                inp.addEventListener("input", function(e) {
                    var a, b, i, val = this.value;
                    closeAllLists();
                    if (!val) { return false; }
                    currentFocus = -1;
                    
                    a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");
                    this.parentNode.appendChild(a);
                    
                    for (i = 0; i < arr.length; i++) {
                        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                            b = document.createElement("DIV");
                            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                            b.innerHTML += arr[i].substr(val.length);
                            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                            b.addEventListener("click", function(e) {
                                inp.value = this.getElementsByTagName("input")[0].value;
                                closeAllLists();
                            });
                            a.appendChild(b);
                        }
                    }
                });

                inp.addEventListener("keydown", function(e) {
                    var x = document.getElementById(this.id + "autocomplete-list");
                    if (x) x = x.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        currentFocus++;
                        addActive(x);
                    } else if (e.keyCode == 38) {
                        currentFocus--;
                        addActive(x);
                    } else if (e.keyCode == 13) {
                        e.preventDefault();
                        if (currentFocus > -1) {
                            if (x) x[currentFocus].click();
                        }
                    }
                });

                function addActive(x) {
                    if (!x) return false;
                    removeActive(x);
                    if (currentFocus >= x.length) currentFocus = 0;
                    if (currentFocus < 0) currentFocus = (x.length - 1);
                    x[currentFocus].classList.add("autocomplete-active");
                }

                function removeActive(x) {
                    for (var i = 0; i < x.length; i++) {
                        x[i].classList.remove("autocomplete-active");
                    }
                }

                function closeAllLists(elmnt) {
                    var x = document.getElementsByClassName("autocomplete-items");
                    for (var i = 0; i < x.length; i++) {
                        if (elmnt != x[i] && elmnt != inp) {
                            x[i].parentNode.removeChild(x[i]);
                        }
                    }
                }

                document.addEventListener("click", function (e) {
                    closeAllLists(e.target);
                });
            }

            autocomplete(document.getElementById("search"), choferes);
        });
    </script>
</head>
<body>
    <div class="sidebar">
        <a href="../index.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Regresar</a>
        <h2>Opciones</h2>
        <a href="index.php">Ver Todo</a>
        <a href="index.php?view=today">Ver Hoy</a>
        <a href="#" onclick="openCreateModal()">Agregar Chofer</a>
    </div>
    <div class="container">
        <h1>Gestion de Choferes</h1>
        
        <h2>Lista de choferes</h2>
        <div class="search-container">
            <form method="get" action="index.php" autocomplete="off">
                <input type="text" name="search" id="search" placeholder="Buscar por nombre de chofer">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Buscar</button>
                <div id="autocomplete-list" class="autocomplete-items"></div>
            </form>
        </div>
        <form method="get" action="index.php" class="form-inline">
            <label for="order">Ordenar por:</label>
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="id_asc" <?php if (isset($_GET['order']) && $_GET['order'] == 'id_asc') echo 'selected'; ?>>ID (Ascendente)</option>
                <option value="id_desc" <?php if (isset($_GET['order']) && $_GET['order'] == 'id_desc') echo 'selected'; ?>>ID (Descendente)</option>
                <option value="mes" <?php if (isset($_GET['order']) && $_GET['order'] == 'mes') echo 'selected'; ?>>Mes</option>
                <option value="hora" <?php if (isset($_GET['order']) && $_GET['order'] == 'hora') echo 'selected'; ?>>Hora</option>
            </select>
            <label for="showAllFields">Mostrar todos los campos:</label>
            <input type="checkbox" id="showAllFields" name="showAllFields" onchange="this.form.submit()" <?php if (isset($_GET['showAllFields']) && $_GET['showAllFields'] == 'on') echo 'checked'; ?>>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Chofer</th>
                <th>Cod1</th>
                <th>Patente</th>
                <th class="wide-column">F. Salida</th>
                <th class="wide-column">F. Ingreso</th>
                <th>H. Salida</th>
                <th>H. Ingreso</th>
                <th>T. Ocupado</th>
                <?php if (isset($_GET['showAllFields']) && $_GET['showAllFields'] == 'on'): ?>
                <th>Cod2</th>
                <th>Lugar</th>
                <th>Detalle</th>
                <?php endif; ?>
                <th>K. Ingreso</th>
                <th>K. Salida</th>
                <th>K. Ocupado</th>
                <th>Acciones</th>
            </tr>
            <?php
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $sqlSearch = "SELECT * FROM data_chofer WHERE LOWER(Chofer) LIKE LOWER('%$search%')";
                $resultSearch = $conn->query($sqlSearch);
                while($row = $resultSearch->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['Chofer']; ?></td>
                <td><?php echo $row['cod1']; ?></td>
                <td><?php echo $row['Patente']; ?></td>
                <td class="wide-column"><?php echo $row['F_Salida']; ?></td>
                <td class="wide-column"><?php echo $row['F_Ingreso']; ?></td>
                <td><?php echo $row['H_Sal']; ?></td>
                <td><?php echo $row['H_ing']; ?></td>
                <td><?php echo $row['T_Ocupado']; ?></td>
                <?php if (isset($_GET['showAllFields']) && $_GET['showAllFields'] == 'on'): ?>
                <td><?php echo $row['Cod2']; ?></td>
                <td><?php echo $row['Lugar']; ?></td>
                <td><?php echo $row['Detalle']; ?></td>
                <?php endif; ?>
                <td><?php echo $row['K_Ing']; ?></td>
                <td><?php echo $row['K_Sal']; ?></td>
                <td><?php echo $row['K_Ocup']; ?></td>
                <td class="actions">
                    <form method="post" action="chofer_crud.php" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button class="btn btn-danger" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    <button class="btn btn-primary" onclick="openEditModal(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i></button>
                    <?php if (!isset($_GET['showAllFields']) || $_GET['showAllFields'] != 'on'): ?>
                    <button class="btn btn-info" onclick="openViewModal(<?php echo $row['id']; ?>)"><i class="fas fa-eye"></i></button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; } elseif (isset($_GET['view']) && $_GET['view'] == 'today') { ?>
            <?php while($row = $resultHoy->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['Chofer']; ?></td>
                <td><?php echo $row['cod1']; ?></td>
                <td><?php echo $row['Patente']; ?></td>
                <td class="wide-column"><?php echo $row['F_Salida']; ?></td>
                <td class="wide-column"><?php echo $row['F_Ingreso']; ?></td>
                <td><?php echo $row['H_Sal']; ?></td>
                <td><?php echo $row['H_ing']; ?></td>
                <td><?php echo $row['T_Ocupado']; ?></td>
                <?php if (isset($_GET['showAllFields']) && $_GET['showAllFields'] == 'on'): ?>
                <td><?php echo $row['Cod2']; ?></td>
                <td><?php echo $row['Lugar']; ?></td>
                <td><?php echo $row['Detalle']; ?></td>
                <?php endif; ?>
                <td><?php echo $row['K_Ing']; ?></td>
                <td><?php echo $row['K_Sal']; ?></td>
                <td><?php echo $row['K_Ocup']; ?></td>
                <td class="actions">
                    <form method="post" action="chofer_crud.php" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button class="btn btn-danger" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    <button class="btn btn-primary" onclick="openEditModal(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i></button>
                    <?php if (!isset($_GET['showAllFields']) || $_GET['showAllFields'] != 'on'): ?>
                    <button class="btn btn-info" onclick="openViewModal(<?php echo $row['id']; ?>)"><i class="fas fa-eye"></i></button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; } else { ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['Chofer']; ?></td>
                <td><?php echo $row['cod1']; ?></td>
                <td><?php echo $row['Patente']; ?></td>
                <td class="wide-column"><?php echo $row['F_Salida']; ?></td>
                <td class="wide-column"><?php echo $row['F_Ingreso']; ?></td>
                <td><?php echo $row['H_Sal']; ?></td>
                <td><?php echo $row['H_ing']; ?></td>
                <td><?php echo $row['T_Ocupado']; ?></td>
                <?php if (isset($_GET['showAllFields']) && $_GET['showAllFields'] == 'on'): ?>
                <td><?php echo $row['Cod2']; ?></td>
                <td><?php echo $row['Lugar']; ?></td>
                <td><?php echo $row['Detalle']; ?></td>
                <?php endif; ?>
                <td><?php echo $row['K_Ing']; ?></td>
                <td><?php echo $row['K_Sal']; ?></td>
                <td><?php echo $row['K_Ocup']; ?></td>
                <td class="actions">
                    <form method="post" action="chofer_crud.php" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button class="btn btn-danger" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    <button class="btn btn-primary" onclick="openEditModal(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i></button>
                    <?php if (!isset($_GET['showAllFields']) || $_GET['showAllFields'] != 'on'): ?>
                    <button class="btn btn-info" onclick="openViewModal(<?php echo $row['id']; ?>)"><i class="fas fa-eye"></i></button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; } ?>
        </table>

        <!-- Modal para agregar -->
        <div id="createModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeCreateModal()">&times;</span>
                <h2>Agregar Chofer</h2>
                <form method="post" action="chofer_crud.php">
                    <div class="form-group">
                        <input type="text" id="create_chofer" name="chofer" placeholder="Chofer" required oninput="autoFillCod1('create')">
                        <input type="number" id="create_cod1" name="cod1" placeholder="Cod1">
                        <input type="text" name="patente" placeholder="Patente">
                    </div>
                    <div class="form-group">
                        <input type="date" name="f_salida" placeholder="F. Salida">
                        <input type="date" name="f_ingreso" placeholder="F. Ingreso">
                        <input type="time" id="create_h_sal" name="h_sal" placeholder="H. Salida" onchange="calculateTOcupado('create')">
                    </div>
                    <div class="form-group">
                        <input type="time" id="create_h_ing" name="h_ing" placeholder="H. Ingreso" onchange="calculateTOcupado('create')">
                        <input type="text" id="create_t_ocupado" name="t_ocupado" placeholder="T. Ocupado" readonly>
                        <input type="number" name="cod2" placeholder="Cod2">
                    </div>
                    <div class="form-group">
                        <input type="text" name="lugar" placeholder="Lugar">
                        <input type="text" name="detalle" placeholder="Detalle">
                        <input type="number" name="k_ing" placeholder="K. Ingreso">
                    </div>
                    <div class="form-group">
                        <input type="number" name="k_sal" placeholder="K. Salida">
                        <input type="number" name="k_ocup" placeholder="K. Ocupado">
                    </div>
                    <button class="btn btn-primary" type="submit" name="create">Crear</button>
                </form>
            </div>
        </div>

        <!-- Modal para editar -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Editar Chofer</h2>
                <form method="post" action="chofer_crud.php">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <input type="text" id="edit_chofer" name="chofer" required oninput="autoFillCod1('edit')">
                        <input type="number" id="edit_cod1" name="cod1">
                        <input type="text" id="edit_patente" name="patente">
                    </div>
                    <div class="form-group">
                        <input type="date" id="edit_f_salida" name="f_salida">
                        <input type="date" id="edit_f_ingreso" name="f_ingreso">
                        <input type="time" id="edit_h_sal" name="h_sal" onchange="calculateTOcupado('edit')">
                    </div>
                    <div class="form-group">
                        <input type="time" id="edit_h_ing" name="h_ing" onchange="calculateTOcupado('edit')">
                        <input type="text" id="edit_t_ocupado" name="t_ocupado" readonly>
                        <input type="number" id="edit_cod2" name="cod2">
                    </div>
                    <div class="form-group">
                        <input type="text" id="edit_lugar" name="lugar">
                        <input type="text" id="edit_detalle" name="detalle">
                        <input type="number" id="edit_k_ing" name="k_ing">
                    </div>
                    <div class="form-group">
                        <input type="number" id="edit_k_sal" name="k_sal">
                        <input type="number" id="edit_k_ocup" name="k_ocup">
                    </div>
                    <button class="btn btn-primary" type="submit" name="update">Actualizar</button>
                </form>
            </div>
        </div>

        <!-- Modal para ver -->
        <div id="viewModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeViewModal()">&times;</span>
                <h2>Ver Chofer</h2>
                <div id="viewContent" class="view-content"></div>
            </div>
        </div>

        <!-- Modal para mensajes -->
        <div id="messageModal" class="modal">
            <div class="message-modal">
                <span class="close" onclick="closeMessageModal()">&times;</span>
                <p id="messageContent"></p>
            </div>
        </div>
    </div>

    <script>
        // Sistema de logging mejorado
        const Logger = {
            error: function(message, error = null) {
                console.error('🔴 ERROR:', message);
                if (error) {
                    console.error('Detalles del error:', {
                        mensaje: error.message,
                        pila: error.stack,
                        origen: error.fileName,
                        linea: error.lineNumber
                    });
                }
                return message;
            },
            info: function(message, data = null) {
                console.info('ℹ️ INFO:', message);
                if (data) {
                    console.info('Datos:', data);
                }
                return message;
            },
            warn: function(message) {
                console.warn('⚠️ ADVERTENCIA:', message);
                return message;
            },
            debug: function(context, data) {
                console.debug('🔍 DEBUG:', context, data);
            }
        };

        // Función para mostrar mensajes
        function showMessage(message, type = 'info') {
            Logger.info(`Mostrando mensaje de tipo ${type}:`, message);
            
            const messageModal = document.getElementById('messageModal');
            const messageContent = document.getElementById('messageContent');
            
            if (!messageModal || !messageContent) {
                return Logger.error('Elementos del modal de mensaje no encontrados');
            }

            messageContent.className = `message ${type}`;
            messageContent.textContent = message;
            messageModal.classList.add('show');

            if (type !== 'error') {
                setTimeout(() => {
                    closeMessageModal();
                }, 3000);
            }
        }

        // Funciones de modal
        function openCreateModal() {
            Logger.debug('openCreateModal', 'Intentando abrir modal de creación');
            const modal = document.getElementById('createModal');
            if (modal) {
                modal.classList.add('show');
                Logger.info('Modal de creación abierto exitosamente');
            } else {
                Logger.error('Modal de creación no encontrado');
                showMessage('Error: No se encontró el modal de creación', 'error');
            }
        }

        async function openEditModal(id) {
            Logger.debug('openEditModal', { id });
            
            if (!id) {
                Logger.error('ID no válido para edición');
                showMessage('ID no válido', 'error');
                return;
            }

            try {
                showMessage('Cargando datos...', 'info');
                Logger.info(`Solicitando datos para ID: ${id}`);
                
                const response = await fetch(`get_chofer.php?id=${id}`);
                Logger.debug('Respuesta del servidor', { 
                    status: response.status, 
                    ok: response.ok 
                });
                
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status}`);
                }

                const data = await response.json();
                Logger.debug('Datos recibidos', data);
                
                if (!data.success) {
                    throw new Error(data.message || 'Error al cargar los datos');
                }

                const modal = document.getElementById('editModal');
                if (!modal) {
                    throw new Error('Modal de edición no encontrado');
                }

                const fieldMappings = {
                    'id': 'id',
                    'chofer': 'Chofer',
                    'cod1': 'cod1',
                    'patente': 'Patente',
                    'f_salida': 'F_Salida',
                    'f_ingreso': 'F_Ingreso',
                    'h_sal': 'H_Sal',
                    'h_ing': 'H_ing',
                    't_ocupado': 'T_Ocupado',
                    'cod2': 'Cod2',
                    'lugar': 'Lugar',
                    'detalle': 'Detalle',
                    'k_ing': 'K_Ing',
                    'k_sal': 'K_Sal',
                    'k_ocup': 'K_Ocup'
                };

                Object.entries(fieldMappings).forEach(([field, dataField]) => {
                    const element = document.getElementById(`edit_${field}`);
                    if (element) {
                        const value = data.data[dataField];
                        element.value = value !== null && value !== undefined ? value : '';
                        Logger.debug(`Campo actualizado`, { field, value });
                    } else {
                        Logger.warn(`Elemento edit_${field} no encontrado`);
                    }
                });

                modal.classList.add('show');
                Logger.info('Modal de edición mostrado exitosamente');
                showMessage('Datos cargados correctamente', 'success');

            } catch (error) {
                Logger.error('Error en openEditModal', error);
                showMessage(error.message, 'error');
            }
        }

        async function openViewModal(id) {
            Logger.debug('openViewModal', { id });
            
            if (!id) {
                Logger.error('ID no válido para vista');
                showMessage('ID no válido', 'error');
                return;
            }

            try {
                showMessage('Cargando datos...', 'info');
                Logger.info(`Solicitando datos para ID: ${id}`);
                
                const response = await fetch(`get_chofer.php?id=${id}`);
                Logger.debug('Respuesta del servidor', { 
                    status: response.status, 
                    ok: response.ok 
                });
                
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status}`);
                }

                const data = await response.json();
                Logger.debug('Datos recibidos', data);
                
                if (!data.success) {
                    throw new Error(data.message || 'Error al cargar los datos');
                }

                const modal = document.getElementById('viewModal');
                const viewContent = document.getElementById('viewContent');
                
                if (!modal || !viewContent) {
                    throw new Error('Modal de vista o contenido no encontrado');
                }

                viewContent.innerHTML = `
                    <p><strong>ID:</strong> ${data.data.id || '-'}</p>
                    <p><strong>Chofer:</strong> ${data.data.Chofer || '-'}</p>
                    <p><strong>Cod1:</strong> ${data.data.cod1 || '-'}</p>
                    <p><strong>Patente:</strong> ${data.data.Patente || '-'}</p>
                    <p><strong>F. Salida:</strong> ${data.data.F_Salida || '-'}</p>
                    <p><strong>F. Ingreso:</strong> ${data.data.F_Ingreso || '-'}</p>
                    <p><strong>H. Salida:</strong> ${data.data.H_Sal || '-'}</p>
                    <p><strong>H. Ingreso:</strong> ${data.data.H_ing || '-'}</p>
                    <p><strong>T. Ocupado:</strong> ${data.data.T_Ocupado || '-'}</p>
                    <p><strong>Cod2:</strong> ${data.data.Cod2 || '-'}</p>
                    <p><strong>Lugar:</strong> ${data.data.Lugar || '-'}</p>
                    <p><strong>Detalle:</strong> ${data.data.Detalle || '-'}</p>
                    <p><strong>K. Ingreso:</strong> ${data.data.K_Ing || '-'}</p>
                    <p><strong>K. Salida:</strong> ${data.data.K_Sal || '-'}</p>
                    <p><strong>K. Ocupado:</strong> ${data.data.K_Ocup || '-'}</p>
                `;

                modal.classList.add('show');
                Logger.info('Modal de vista mostrado exitosamente');
                showMessage('Datos cargados correctamente', 'success');

            } catch (error) {
                Logger.error('Error en openViewModal', error);
                showMessage(error.message, 'error');
            }
        }

        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            if (modal) {
                modal.classList.remove('show');
                Logger.info('Modal de creación cerrado');
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            if (modal) {
                modal.classList.remove('show');
                Logger.info('Modal de edición cerrado');
            }
        }

        function closeViewModal() {
            const modal = document.getElementById('viewModal');
            if (modal) {
                modal.classList.remove('show');
                Logger.info('Modal de vista cerrado');
            }
        }

        function closeMessageModal() {
            const modal = document.getElementById('messageModal');
            if (modal) {
                modal.classList.remove('show');
                Logger.info('Modal de mensaje cerrado');
            }
        }

        // Manejador global de errores
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            const errorDetails = {
                mensaje: msg,
                archivo: url,
                linea: lineNo,
                columna: columnNo,
                error: error
            };
            
            Logger.error('Error global capturado', errorDetails);
            showMessage('Ha ocurrido un error. Revisa la consola para más detalles.', 'error');
            return false;
        };

        // Manejador de promesas rechazadas
        window.addEventListener('unhandledrejection', function(event) {
            Logger.error('Promesa rechazada no manejada', {
                razon: event.reason,
                promesa: event.promise
            });
        });

        // Función para calcular el tiempo ocupado
        function calculateTOcupado(modalType) {
            try {
                Logger.debug('calculateTOcupado', { modalType });
                
                const hSal = document.getElementById(`${modalType}_h_sal`).value;
                const hIng = document.getElementById(`${modalType}_h_ing`).value;
                
                if (!hSal || !hIng) {
                    Logger.info('Valores de hora incompletos');
                    return;
                }

                const [hSalHour, hSalMinute] = hSal.split(':').map(Number);
                const [hIngHour, hIngMinute] = hIng.split(':').map(Number);
                
                if (isNaN(hSalHour) || isNaN(hSalMinute) || isNaN(hIngHour) || isNaN(hIngMinute)) {
                    throw new Error('Formato de hora inválido');
                }

                let diffHours = hIngHour - hSalHour;
                let diffMinutes = hIngMinute - hSalMinute;

                if (diffMinutes < 0) {
                    diffHours--;
                    diffMinutes += 60;
                }

                if (diffHours < 0) {
                    diffHours += 24;
                }

                const resultado = `${String(diffHours).padStart(2, '0')}:${String(diffMinutes).padStart(2, '0')}`;
                document.getElementById(`${modalType}_t_ocupado`).value = resultado;
                Logger.debug('Tiempo ocupado calculado', { resultado });
                
            } catch (error) {
                Logger.error('Error al calcular tiempo ocupado', error);
                showMessage('Error al calcular el tiempo ocupado: ' + error.message, 'error');
            }
        }

        // Función para autocompletar el código
        function autoFillCod1(modalType) {
            const chofer = document.querySelector(`#${modalType}_chofer`).value;
            if (chofer) {
                fetch('get_cod1.php?chofer=' + chofer)
                    .then(response => response.json())
                    .then(data => {
                        if (data.cod1) {
                            document.querySelector(`#${modalType}_cod1`).value = data.cod1;
                        }
                    });
            }
        }

        // Mostrar mensaje inicial si existe
        <?php
        $message = ErrorHandler::showMessage();
        if ($message): ?>
            showMessage('<?php echo addslashes($message['message']); ?>', '<?php echo $message['type']; ?>');
        <?php endif; ?>
    </script>


</body>
</html>
