<?php
include 'conexion.php';

$origen = $_POST['origen'];
$destino = $_POST['destino'];
$fecha = $_POST['fecha'];
$plazas_disponibles = $_POST['plazas_disponibles'];
$precio = $_POST['precio'];

$sql = "INSERT INTO VUELO (origen, destino, fecha, plazas_disponibles, precio)
VALUES ('$origen', '$destino', '$fecha', $plazas_disponibles, $precio)";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo vuelo registrado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
