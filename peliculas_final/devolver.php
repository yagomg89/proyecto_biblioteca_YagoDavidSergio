<?php
// Iniciamos la sesión
session_start();
include "internacionalizacion.php";
include "conexion_db.php";

// Verificamos si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Variables para los mensajes y datos
$mensaje = "";
$error = "";
$reserva_id = null;
$producto_tipo = null;

// Procesamos la devolucion si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id']) && isset($_POST['producto_tipo'])) {
    $reserva_id = $_POST['reserva_id'];
    $producto_tipo = $_POST['producto_tipo'];
    
    // Obtenemos el ID del producto según el tipo
    if ($producto_tipo === "libro") {
        $sql_reserva = "SELECT ID_LIBRO FROM reserva WHERE ID = $reserva_id";
    } else {
        $sql_reserva = "SELECT ID_PELICULA FROM reserva WHERE ID = $reserva_id";
    }
    
    // Ejecutamos la consulta
    $resultado_reserva = $conexion->query($sql_reserva);
    
    // Verificamos que encontramos la reserva
    if ($resultado_reserva && $resultado_reserva->num_rows > 0) {
        $reserva = $resultado_reserva->fetch_assoc();
        // Obtenemos el ID del producto según el tipo
        $producto_id = $producto_tipo === "libro" ? $reserva['ID_LIBRO'] : $reserva['ID_PELICULA'];
        
        // Marcamos la reserva como devuelta en la base de datos
        $sql_update_reserva = "UPDATE reserva SET DEVUELTO = TRUE WHERE ID = $reserva_id";
        
        if ($conexion->query($sql_update_reserva)) {
            // Determinamos en qué tabla actualizar la disponibilidad
            $tabla = ($producto_tipo === "libro") ? "Libros" : "peliculas";
            // Marcamos el producto como disponible nuevamente
            $sql_update_producto = "UPDATE $tabla SET DISPONIBLE = TRUE WHERE ID = $producto_id";
            
            if ($conexion->query($sql_update_producto)) {
                // Limpiamos el catalogo de la sesión para que se recargue con los datos actualizados
                if ($producto_tipo === "libro") {
                    unset($_SESSION['libros_catalogo']);
                } else {
                    unset($_SESSION['peliculas_catalogo']);
                }
                
                $mensaje = "¡Devolucion registrada con éxito! El producto está disponible de nuevo.";
            } else {
                $error = "Error al actualizar la disponibilidad: " . $conexion->error;
            }
        } else {
            $error = "Error al registrar la devolucion: " . $conexion->error;
        }
    } else {
        $error = "Reserva no encontrada";
    }
} else {
    // Si no hay datos POST redirigimos al mene principal
    header("Location: fichero.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $traducciones["devolucion"]?></title>
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #1f3b4d;
            --muted: #6b7785;
            --accent: #2b7cc3;
            --success: #10b981;
            --error: #ef4444;
        }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: var(--card);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(35, 47, 63, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            color: var(--accent);
            margin-bottom: 24px;
            font-size: 1.8rem;
        }
        .mensaje {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 600;
            font-size: 1rem;
        }
        .mensaje.exito {
            background: #d1fae5;
            color: var(--success);
            border: 2px solid var(--success);
        }
        .mensaje.error {
            background: #fee2e2;
            color: var(--error);
            border: 2px solid var(--error);
        }
        .icono {
            font-size: 3rem;
            margin-bottom: 16px;
        }
        .botones {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
        }
        a.boton, input[type="submit"] {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        a.boton:hover, input[type="submit"]:hover {
            background: #1f57a0;
            transform: scale(1.03);
        }
        .boton-catalogo {
            background: var(--success);
        }
        .boton-catalogo:hover {
            background: #059669;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (!empty($mensaje)): ?>
            <h1><?php echo $traducciones["exito"]?></h1>
            <div class="mensaje exito"><?php echo $mensaje; ?></div>
            
            <div class="botones">
                <a href="reservas.php" class="boton"><?php echo $traducciones["verReservas"]?></a>
                <a href="catalogo.php" class="boton boton-catalogo"><?php echo $traducciones["irCatalogo"]?></a>
            </div>
            
        <?php elseif (!empty($error)): ?>
            <h1><?php echo $traducciones["error_titulo"]?></h1>
            <div class="mensaje error"><?php echo $error; ?></div>
            
            <div class="botones">
                <a href="reservas.php" class="boton"><?php echo $traducciones["volver"]?></a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>