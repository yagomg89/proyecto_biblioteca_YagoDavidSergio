<?php
// Iniciamos la sesión
session_start();
include "conexion_db.php";
include "internacionalizacion.php";
include "traits.php";

// Verificamos si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

class HistorialReservas {
    use FormatoFecha;
}

// Obtenemos todas las reservas (películas, series y libros)
$reservas = [];
$consulta = "SELECT r.ID, r.FECHA_RESERVA, r.DEVUELTO,
                    c.NOMBRE, c.APELLIDOS,
                    p.Titulo as TITULO_PELICULA, p.DIRECTOR, p.GENERO as GENERO_PELICULA,
                    l.TITULO as TITULO_LIBRO, l.GENERO as GENERO_LIBRO, l.EDITORIAL,
                    a.NOMBRE as AUTOR_NOMBRE
             FROM reserva r
             JOIN clientes c ON r.CLIENTE_ID = c.ID
             LEFT JOIN peliculas p ON r.ID_PELICULA = p.ID
             LEFT JOIN Libros l ON r.ID_LIBRO = l.ID
             LEFT JOIN autores a ON l.AUTOR_ID = a.ID
             ORDER BY r.FECHA_RESERVA DESC";
$resultado = $conexion->query($consulta);

// Si hay reservas, las guardamos en el array
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $reservas[] = $row;
    }
}

$help = new HistorialReservas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $traducciones["historicoReservas"] ?? "Histórico de Reservas"; ?></title>
    <style>
        :root {
            --bg: #e8f0fa;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --shadow: rgba(0, 0, 0, 0.08);
            --success: #10b981;
            --warning: #f59e0b;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            padding: 0;
        }

        header {
            background: var(--accent);
            color: #fff;
            padding: 16px 0;
            text-align: center;
            box-shadow: 0 2px 8px var(--shadow);
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
        }

        .lang-selector {
            margin-top: 8px;
        }

        .lang-selector a {
            color: #fff;
            text-decoration: none;
            margin: 0 6px;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .lang-selector a:hover {
            opacity: 0.85;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #f9fbff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 14px var(--shadow);
        }

        .reserva-item {
            background: var(--card);
            border-radius: 10px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px var(--shadow);
            margin-bottom: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .reserva-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px var(--shadow);
        }

        .reserva-item h3 {
            margin: 0 0 8px 0;
            font-size: 1.15rem;
            color: var(--accent);
        }

        .reserva-item p {
            margin: 6px 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .reserva-item strong {
            color: var(--text);
        }

        .tipo-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 8px;
        }

        .tipo-badge.pelicula {
            background: #dbeafe;
            color: #1e40af;
        }

        .tipo-badge.libro {
            background: #fce7f3;
            color: #9f1239;
        }

        .estado {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .estado.devuelto {
            background: #d1fae5;
            color: var(--success);
        }

        .estado.activo {
            background: #fef3c7;
            color: var(--warning);
        }

        .no-results {
            text-align: center;
            color: var(--muted);
            font-size: 1rem;
            margin: 40px 0;
        }

        .botones {
            text-align: center;
            margin-top: 30px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        a.boton {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
        }

        a.boton:hover {
            background: var(--accent-hover);
            transform: scale(1.03);
        }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 0.9rem;
            color: var(--muted);
        }
    </style>
</head>
<body>

<header>
    <h1><?php echo $traducciones["historicoReservas"]; ?></h1>
    <div class="lang-selector"> 
        <a href="?lang=es">Español</a> |
        <a href="?lang=en">English</a>
    </div>
</header>

<div class="container">
    <?php if (count($reservas) > 0): ?>
        <?php foreach ($reservas as $reserva): ?>
            <div class="reserva-item">
                <?php 
                // Determinamos si es película/serie o libro
                $es_pelicula = !empty($reserva['TITULO_PELICULA']);
                $titulo = $es_pelicula ? $reserva['TITULO_PELICULA'] : $reserva['TITULO_LIBRO'];
                ?>
                
                <h3>
                    <?php echo htmlspecialchars($titulo); ?>
                </h3>
                
                <?php if ($es_pelicula): ?>
                    <p><strong><?php echo $traducciones["director"]?>:</strong> <?php echo htmlspecialchars($reserva['DIRECTOR']); ?></p>
                    <p><strong><?php echo $traducciones["genero"]?>:</strong> <?php echo htmlspecialchars($reserva['GENERO_PELICULA']); ?></p>
                <?php else: ?>
                    <p><strong><?php echo $traducciones["autor"]?>:</strong> <?php echo htmlspecialchars($reserva['AUTOR_NOMBRE'] ?? 'Desconocido'); ?></p>
                    <p><strong><?php echo $traducciones["genero"]?>:</strong> <?php echo htmlspecialchars($reserva['GENERO_LIBRO']); ?></p>
                    <p><strong><?php echo $traducciones["editorial"]?>:</strong> <?php echo htmlspecialchars($reserva['EDITORIAL']); ?></p>
                <?php endif; ?>
                
                <p><strong><?php echo $traducciones["cliente"]?>:</strong> <?php echo htmlspecialchars($reserva['NOMBRE'] . ' ' . $reserva['APELLIDOS']); ?></p>
                <p><strong><?php echo $traducciones["fecha"] ?? "Fecha de reserva"; ?>:</strong> 
                <?php echo $help->formatearFecha($reserva['FECHA_RESERVA']); ?></p>
                
                <?php if ($reserva['DEVUELTO']): ?>
                    <span class="estado devuelto"><?php echo $traducciones["devuelto"];?></span>
                <?php else: ?>
                    <span class="estado activo"><?php echo $traducciones["activo"];?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results"><?php echo $traducciones["noReservas"];?></p>
    <?php endif; ?>

    <div class="botones">
        <a href="fichero.php" class="boton"><?php echo $traducciones["volver"]; ?></a>
    </div>
</div>

<footer>
    <?php echo $traducciones["language"]; ?>
</footer>

</body>
</html>