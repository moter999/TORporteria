<?php
include '../../../conexion/conectado.php';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM data_chofer WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Chofer</title>
</head>
<body>
    <h1>Actualizar Chofer</h1>
    <form method="post" action="chofer_crud.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="chofer" value="<?php echo $row['Chofer']; ?>" required>
        <input type="number" name="cod1" value="<?php echo $row['cod1']; ?>">
        <input type="text" name="patente" value="<?php echo $row['Patente']; ?>">
        <input type="text" name="f_salida" value="<?php echo $row['F_Salida']; ?>">
        <input type="text" name="f_ingreso" value="<?php echo $row['F_Ingreso']; ?>">
        <input type="text" name="h_sal" value="<?php echo $row['H_Sal']; ?>">
        <input type="text" name="h_ing" value="<?php echo $row['H_ing']; ?>">
        <input type="text" name="t_ocupado" value="<?php echo $row['T_Ocupado']; ?>">
        <input type="number" name="cod2" value="<?php echo $row['Cod2']; ?>">
        <input type="text" name="lugar" value="<?php echo $row['Lugar']; ?>">
        <input type="text" name="detalle" value="<?php echo $row['Detalle']; ?>">
        <input type="number" name="k_ing" value="<?php echo $row['K_Ing']; ?>">
        <input type="number" name="k_sal" value="<?php echo $row['K_Sal']; ?>">
        <input type="number" name="k_ocup" value="<?php echo $row['K_Ocup']; ?>">
        <button type="submit" name="update">Actualizar</button>
    </form>
</body>
</html>
