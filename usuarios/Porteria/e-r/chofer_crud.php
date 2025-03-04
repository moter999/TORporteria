<?php
session_start();
include '../../../conexion/conectado.php';
include 'error_handler.php';

function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function validateTime($time) {
    return preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time);
}

try {
    // Crear
    if (isset($_POST['create'])) {
        // Validación y sanitización de campos
        $chofer = sanitizeInput($_POST['chofer']);
        $cod1 = sanitizeInput($_POST['cod1']);
        $patente = sanitizeInput($_POST['patente']);
        $f_salida = sanitizeInput($_POST['f_salida']);
        $f_ingreso = sanitizeInput($_POST['f_ingreso']);
        $h_sal = sanitizeInput($_POST['h_sal']);
        $h_ing = sanitizeInput($_POST['h_ing']);
        $t_ocupado = sanitizeInput($_POST['t_ocupado']);
        $cod2 = sanitizeInput($_POST['cod2']);
        $lugar = sanitizeInput($_POST['lugar']);
        $detalle = sanitizeInput($_POST['detalle']);
        $k_ing = is_numeric($_POST['k_ing']) ? $_POST['k_ing'] : 0;
        $k_sal = is_numeric($_POST['k_sal']) ? $_POST['k_sal'] : 0;
        $k_ocup = is_numeric($_POST['k_ocup']) ? $_POST['k_ocup'] : 0;

        // Validaciones
        if (empty($chofer) || empty($patente)) {
            throw new Exception("Los campos Chofer y Patente son obligatorios");
        }

        if (!empty($f_salida) && !validateDate($f_salida)) {
            throw new Exception("Formato de fecha de salida inválido");
        }

        if (!empty($f_ingreso) && !validateDate($f_ingreso)) {
            throw new Exception("Formato de fecha de ingreso inválido");
        }

        if (!empty($h_sal) && !validateTime($h_sal)) {
            throw new Exception("Formato de hora de salida inválido");
        }

        if (!empty($h_ing) && !validateTime($h_ing)) {
            throw new Exception("Formato de hora de ingreso inválido");
        }

        $stmt = $conn->prepare("INSERT INTO data_chofer (Chofer, cod1, Patente, F_Salida, F_Ingreso, H_Sal, H_ing, T_Ocupado, Cod2, Lugar, Detalle, K_Ing, K_Sal, K_Ocup) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssddd", $chofer, $cod1, $patente, $f_salida, $f_ingreso, $h_sal, $h_ing, $t_ocupado, $cod2, $lugar, $detalle, $k_ing, $k_sal, $k_ocup);

        if ($stmt->execute()) {
            ErrorHandler::handleError("Registro creado exitosamente", "success");
        } else {
            throw new Exception("Error al crear el registro");
        }
    }

    // Actualizar
    if (isset($_POST['update'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID inválido");
        }

        // Validación y sanitización similar a create
        $chofer = sanitizeInput($_POST['chofer']);
        $cod1 = sanitizeInput($_POST['cod1']);
        $patente = sanitizeInput($_POST['patente']);
        $f_salida = sanitizeInput($_POST['f_salida']);
        $f_ingreso = sanitizeInput($_POST['f_ingreso']);
        $h_sal = sanitizeInput($_POST['h_sal']);
        $h_ing = sanitizeInput($_POST['h_ing']);
        $t_ocupado = sanitizeInput($_POST['t_ocupado']);
        $cod2 = sanitizeInput($_POST['cod2']);
        $lugar = sanitizeInput($_POST['lugar']);
        $detalle = sanitizeInput($_POST['detalle']);
        $k_ing = is_numeric($_POST['k_ing']) ? $_POST['k_ing'] : 0;
        $k_sal = is_numeric($_POST['k_sal']) ? $_POST['k_sal'] : 0;
        $k_ocup = is_numeric($_POST['k_ocup']) ? $_POST['k_ocup'] : 0;

        // Validaciones
        if (empty($chofer) || empty($patente)) {
            throw new Exception("Los campos Chofer y Patente son obligatorios");
        }

        $stmt = $conn->prepare("UPDATE data_chofer SET Chofer=?, cod1=?, Patente=?, F_Salida=?, F_Ingreso=?, H_Sal=?, H_ing=?, T_Ocupado=?, Cod2=?, Lugar=?, Detalle=?, K_Ing=?, K_Sal=?, K_Ocup=? WHERE id=?");
        $stmt->bind_param("sssssssssssdddi", $chofer, $cod1, $patente, $f_salida, $f_ingreso, $h_sal, $h_ing, $t_ocupado, $cod2, $lugar, $detalle, $k_ing, $k_sal, $k_ocup, $id);

        if ($stmt->execute()) {
            ErrorHandler::handleError("Registro actualizado exitosamente", "success");
        } else {
            throw new Exception("Error al actualizar el registro");
        }
    }

    // Eliminar
    if (isset($_POST['delete'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID inválido");
        }

        $stmt = $conn->prepare("DELETE FROM data_chofer WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            ErrorHandler::handleError("Registro eliminado exitosamente", "success");
        } else {
            throw new Exception("Error al eliminar el registro");
        }
    }

} catch (Exception $e) {
    ErrorHandler::handleError($e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>
