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
class ReservasLibros {
    use FormatoFecha;
}

// Obtenemos las reservas activas de libros
$reservas_libros = [];
$consulta = "SELECT r.ID, r.ID_LIBRO, l.TITULO, r.FECHA_RESERVA, r.DEVUELTO,
                    c.NOMBRE, c.APELLIDOS, l.GENERO, l.EDITORIAL,
                    a.NOMBRE as AUTOR_NOMBRE
             FROM reserva r 
             JOIN Libros l ON r.ID_LIBRO = l.ID
             JOIN clientes c ON r.CLIENTE_ID = c.ID
             LEFT JOIN autores a ON l.AUTOR_ID = a.ID
             WHERE r.DEVUELTO = FALSE
             ORDER BY r.FECHA_RESERVA DESC";
$resultado = $conexion->query($consulta);

// Si hay reservas, las guardamos en el array
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $reservas_libros[] = $row;
    }
}

$help = new ReservasLibros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $traducciones["reservas_libros"] ?? "Reservas de Libros"; ?></title>
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
            max-width: 900px;
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

        .reserva-item h2 {
            margin: 0 0 8px 0;
            font-size: 1.25rem;
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

        .no-results {
            text-align: center;
            color: var(--muted);
            font-size: 1.1rem;
            margin: 40px 0;
        }

        .botones {
            text-align: center;
            margin-top: 20px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        a.boton, input[type="submit"], button {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        a.boton:hover, input[type="submit"]:hover, button:hover {
            background: var(--accent-hover);
            transform: scale(1.03);
        }

        .btn-devolver {
            background: var(--success);
            padding: 8px 16px;
            font-size: 0.9rem;
            margin-top: 12px;
        }

        .btn-devolver:hover {
            background: #059669;
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
    <h1><?php echo $traducciones["reservas_libros"] ?? "Reservas de Libros"; ?></h1>
    <div class="lang-selector"> 
        <a href="?lang=es">Español</a> |
        <a href="?lang=en">English</a>
    </div>
</header>

<div class="container">
    <?php if (count($reservas_libros) > 0): ?>
        <?php foreach ($reservas_libros as $reserva): ?>
            <div class="reserva-item">
                <h2><?php echo htmlspecialchars($reserva['TITULO']); ?></h2>
                <p><strong><?php echo $traducciones["autor"] ?? "Autor"; ?>:</strong> <?php echo htmlspecialchars($reserva['AUTOR_NOMBRE'] ?? 'Desconocido'); ?></p>
                <p><strong><?php echo $traducciones["genero"] ?? "Género"; ?>:</strong> <?php echo htmlspecialchars($reserva['GENERO']); ?></p>
                <p><strong><?php echo $traducciones["editorial"] ?? "Editorial"; ?>:</strong> <?php echo htmlspecialchars($reserva['EDITORIAL']); ?></p>
                <p><strong><?php echo $traducciones["cliente"] ?? "Cliente"; ?>:</strong> <?php echo htmlspecialchars($reserva['NOMBRE'] . ' ' . $reserva['APELLIDOS']); ?></p>
                <p><strong><?php echo $traducciones["fecha"] ?? "Fecha de reserva"; ?>:</strong> 
                <?php echo $help->formatearFecha($reserva['FECHA_RESERVA']); ?></p>
                
                <form action="devolver.php" method="POST" style="margin: 0;">
                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['ID']; ?>">
                    <input type="hidden" name="producto_tipo" value="libro">
                    <button type="submit" class="btn-devolver"><?php echo $traducciones["marcarDevuelto"] ?? "Marcar como devuelto"; ?></button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results"><?php echo $traducciones["noReservasLibros"] ?? "No hay reservas activas de libros"; ?></p>
    <?php endif; ?>

    <div class="botones">
        <a href="reservas.php" class="boton"><?php echo $traducciones["volver"] ?? "Volver"; ?></a>
    </div>
</div>

<footer>
    <?php echo $traducciones["language"] ?? "Idioma"; ?>
</footer>

</body>
</html>