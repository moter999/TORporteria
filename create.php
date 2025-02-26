<?php
include '../conexion/conectado.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chofer = $_POST['chofer'];
    $cod1 = $_POST['cod1'];
    $patente = $_POST['patente'];
    $f_salida = isset($_POST['F.Salida']) ? $_POST['F.Salida'] : null;
    $f_ingreso = $_POST['F.Ingreso'];
    $h_salida = isset($_POST['H.Salida']) ? $_POST['H.Salida'] : null;
    $h_ingreso = isset($_POST['H.Ingreso']) ? $_POST['H.Ingreso'] : null;
    $t_ocupado = isset($_POST['T.Ocupado']) ? $_POST['T.Ocupado'] : null;
    $cod2 = isset($_POST['Cod2']) ? $_POST['Cod2'] : null;
    $lugar = isset($_POST['Lugar']) ? $_POST['Lugar'] : null;
    $detalle = isset($_POST['Detalle']) ? $_POST['Detalle'] : null;
    $k_ingreso = isset($_POST['K.Ingreso']) ? $_POST['K.Ingreso'] : null;
    $k_salida = isset($_POST['K.Salida']) ? $_POST['K.Salida'] : null;
    $k_ocupado = isset($_POST['K.Ocupado']) ? $_POST['K.Ocupado'] : null;

    if ($h_ingreso && $h_salida) {
        $t_ocupado = calcularTiempoOcupado($h_ingreso, $h_salida);
    }

    $sql = "INSERT INTO data_chofer (Chofer, cod1, Patente, `F.Salida`, `F.Ingreso`, `H.Salida`, `H.Ingreso`, `T.Ocupado`, Cod2, Lugar, Detalle, `K.Ingreso`, `K.Salida`, `K.Ocupado`)
            VALUES ('$chofer', '$cod1', '$patente', '$f_salida', '$f_ingreso', '$h_salida', '$h_ingreso', '$t_ocupado', '$cod2', '$lugar', '$detalle', '$k_ingreso', '$k_salida', '$k_ocupado')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function calcularTiempoOcupado($h_ingreso, $h_salida) {
    $h_ingreso_parts = explode(':', $h_ingreso);
    $h_salida_parts = explode(':', $h_salida);
    $h_ingreso_minutes = intval($h_ingreso_parts[0]) * 60 + intval($h_ingreso_parts[1]);
    $h_salida_minutes = intval($h_salida_parts[0]) * 60 + intval($h_salida_parts[1]);
    $diff_minutes = $h_ingreso_minutes - $h_salida_minutes;
    $diff_hours = intdiv($diff_minutes, 60);
    $diff_minutes_remaining = $diff_minutes % 60;
    return sprintf('%02d:%02d', $diff_hours, $diff_minutes_remaining);
}

$conn->close();
?>
