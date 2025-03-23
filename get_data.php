<?php
header('Content-Type: application/json');

// Configuración de conexión a MySQL
$host = "www.parabellumgames.es";
$user = "parab_app";  // Cambia esto si usas otro usuario
$pass = "Parabellum2024*";  // Agrega la contraseña si aplica
$dbname = "parab_app";

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

$sql = "SELECT mes, previsiones_descargas, descargas_reales FROM proyectos ORDER BY mes ASC";
$result = $conn->query($sql);

$categorias = [];
$tareas = [];
$proyectos = [];

while ($row = $result->fetch_assoc()) {
    $categorias[] = $row['mes'];
    $tareas[] = $row['previsiones_descargas'];
    $proyectos[] = $row['descargas_reales'];
}

echo json_encode(["categorias" => $categorias, "tareas" => $tareas, "proyectos" => $proyectos]);
$conn->close();
?>
