<?php
include 'conexion.php';

$sql = "SELECT HOTEL.nombre, COUNT(RESERVA.id_reserva) as num_reservas
FROM HOTEL
JOIN RESERVA ON HOTEL.id_hotel = RESERVA.id_hotel
GROUP BY HOTEL.id_hotel
HAVING num_reservas > 2";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Hotel: " . $row["nombre"]. " - Número de Reservas: " . $row["num_reservas"]. "<br>";
    }
} else {
    echo "No hay hoteles con más de dos reservas";
}

$conn->close();
?>
