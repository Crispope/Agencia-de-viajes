<?php
include 'conexion.php';

// Insertar reservas
for ($i = 1; $i <= 10; $i++) {
    $id_cliente = $i;
    $fecha_reserva = date('Y-m-d', strtotime("+$i days"));
    $id_vuelo = rand(1, 3); // Asumiendo que hay al menos 3 vuelos
    $id_hotel = rand(1, 3); // Asumiendo que hay al menos 3 hoteles

    $sql = "INSERT INTO RESERVA (id_cliente, fecha_reserva, id_vuelo, id_hotel)
    VALUES ($id_cliente, '$fecha_reserva', $id_vuelo, $id_hotel)";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mostrar reservas
$sql = "SELECT * FROM RESERVA";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID Reserva: " . $row["id_reserva"]. " - Cliente: " . $row["id_cliente"]. " - Fecha: " . $row["fecha_reserva"]. "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
