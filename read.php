<?php
include '../conexion/conectado.php';

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM data_chofer WHERE Chofer LIKE ? OR cod1 LIKE ? OR Patente LIKE ? OR `F.Salida` LIKE ? OR `F.Ingreso` LIKE ? OR `H.Sal` LIKE ? OR `H.Ing` LIKE ? OR `T.Ocupado` LIKE ? OR Cod2 LIKE ? OR Lugar LIKE ? OR Detalle LIKE ? OR `K.Ing` LIKE ? OR `K.Sal` LIKE ? OR `K.Ocup` LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$searchQuery%";
$stmt->bind_param('ssssssssssssss', $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['Chofer'] . "</td>";
        echo "<td>" . $row['cod1'] . "</td>";
        echo "<td>" . $row['Patente'] . "</td>";
        echo "<td>" . $row['F.Salida'] . "</td>";
        echo "<td>" . $row['F.Ingreso'] . "</td>";
        echo "<td>" . $row['H.Sal'] . "</td>";
        echo "<td>" . $row['H.Ing'] . "</td>";
        echo "<td>" . $row['T.Ocupado'] . "</td>";
        echo "<td>" . $row['Cod2'] . "</td>";
        echo "<td>" . $row['Lugar'] . "</td>";
        echo "<td>" . $row['Detalle'] . "</td>";
        echo "<td>" . $row['K.Ing'] . "</td>";
        echo "<td>" . $row['K.Sal'] . "</td>";
        echo "<td>" . $row['K.Ocup'] . "</td>";
        echo "<td>
                <button class='btn btn-warning btn-sm' onclick='editRecord(" . $row['id'] . ")'>
                    <i class='fas fa-edit'></i>
                </button>
                <button class='btn btn-danger btn-sm' onclick='deleteRecord(" . $row['id'] . ")'>
                    <i class='fas fa-trash'></i>
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='16'>No hay registros</td></tr>";
}

$conn->close();
?>
