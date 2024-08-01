<?php
// Parámetros de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "AGENCIA";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conectado exitosamente<br>";

// Crear las tablas si no existen
$sql = "
CREATE TABLE IF NOT EXISTS VUELO (
    id_vuelo INT AUTO_INCREMENT PRIMARY KEY,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    plazas_disponibles INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);
CREATE TABLE IF NOT EXISTS HOTEL (
    id_hotel INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ubicación VARCHAR(100) NOT NULL,
    habitaciones_disponibles INT NOT NULL,
    tarifa_noche DECIMAL(10, 2) NOT NULL
);
CREATE TABLE IF NOT EXISTS RESERVA (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    id_vuelo INT,
    id_hotel INT,
    FOREIGN KEY (id_vuelo) REFERENCES VUELO(id_vuelo),
    FOREIGN KEY (id_hotel) REFERENCES HOTEL(id_hotel)
);
";
if ($conn->multi_query($sql)) {
    echo "Tablas creadas exitosamente o ya existen.<br>";
} else {
    echo "Error creando tablas: " . $conn->error . "<br>";
}

// Insertar registros en las tablas VUELO y HOTEL si están vacías
$insert_vuelos = "
INSERT INTO VUELO (origen, destino, fecha, plazas_disponibles, precio) VALUES
('New York', 'Los Angeles', '2024-08-01', 100, 300.00),
('London', 'Paris', '2024-08-15', 150, 200.00),
('Tokyo', 'Beijing', '2024-09-01', 120, 400.00)
ON DUPLICATE KEY UPDATE origen=VALUES(origen);
";
$insert_hoteles = "
INSERT INTO HOTEL (nombre, ubicación, habitaciones_disponibles, tarifa_noche) VALUES
('Hotel Central', 'New York', 50, 150.00),
('Hotel Paris', 'Paris', 75, 200.00),
('Hotel Beijing', 'Beijing', 60, 180.00)
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
";

if ($conn->query($insert_vuelos) === TRUE) {
    echo "Vuelos insertados exitosamente o ya existen.<br>";
} else {
    echo "Error insertando vuelos: " . $conn->error . "<br>";
}

if ($conn->query($insert_hoteles) === TRUE) {
    echo "Hoteles insertados exitosamente o ya existen.<br>";
} else {
    echo "Error insertando hoteles: " . $conn->error . "<br>";
}

// Insertar reservas
for ($i = 1; $i <= 10; $i++) {
    $id_cliente = $i;
    $fecha_reserva = date('Y-m-d', strtotime("+$i days"));
    $id_vuelo = rand(1, 3); // Asumiendo que hay al menos 3 vuelos
    $id_hotel = rand(1, 3); // Asumiendo que hay al menos 3 hoteles

    $sql = "INSERT INTO RESERVA (id_cliente, fecha_reserva, id_vuelo, id_hotel)
    VALUES ($id_cliente, '$fecha_reserva', $id_vuelo, $id_hotel)";

    if ($conn->query($sql) !== TRUE) {
        echo "Error insertando reserva: " . $conn->error . "<br>";
    }
}

// Mostrar registros de la tabla RESERVA
$sql = "SELECT * FROM RESERVA";
$result = $conn->query($sql);

echo "<h2>Registros de la tabla RESERVA</h2>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID Reserva: " . $row["id_reserva"]. " - Cliente: " . $row["id_cliente"]. " - Fecha: " . $row["fecha_reserva"]. "<br>";
    }
} else {
    echo "No hay registros en la tabla RESERVA.<br>";
}

// Consulta avanzada: Hoteles con más de dos reservas
$sql = "SELECT HOTEL.nombre, COUNT(RESERVA.id_reserva) as num_reservas
FROM HOTEL
JOIN RESERVA ON HOTEL.id_hotel = RESERVA.id_hotel
GROUP BY HOTEL.id_hotel
HAVING num_reservas > 2";

$result = $conn->query($sql);

echo "<h2>Hoteles con más de dos reservas</h2>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Hotel: " . $row["nombre"]. " - Número de Reservas: " . $row["num_reservas"]. "<br>";
    }
} else {
    echo "No hay hoteles con más de dos reservas.<br>";
}

$conn->close();
?>
