<?php
function obtenerLibros($conexion) {
    $sql = "SELECT * FROM Libros";
    $resultado = $conexion->query($sql);
    $libros = [];

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $libros[] = $fila;
        }
    }

    return $libros;
}
?>