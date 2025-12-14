<?php
function obtenerPeliculas($conexion) {
    $sql = "SELECT * FROM Peliculas";
    $resultado = $conexion->query($sql);
    $peliculas = [];

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $peliculas[] = $fila;
        }
    }

    return $peliculas;
}
?>