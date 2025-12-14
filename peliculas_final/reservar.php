<?php
// Iniciamos la sesion
session_start();
include "internacionalizacion.php";
include "conexion_db.php";

// Verificamos si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Variables para los mensajes
$mensaje = "";
$error = "";

// Obtenemos el ID y tipo de producto desde POST, GET o sesion
$producto_id = $_POST['producto_id'] ?? $_GET['producto_id'] ?? $_SESSION['reserva_producto_id'] ?? null;
$producto_tipo = $_POST['producto_tipo'] ?? $_GET['producto_tipo'] ?? $_SESSION['reserva_producto_tipo'] ?? null;

// Guardamos en sesión para mantener los datos al cambiar idioma
if ($producto_id && $producto_tipo) {
    $_SESSION['reserva_producto_id'] = $producto_id;
    $_SESSION['reserva_producto_tipo'] = $producto_tipo;
}

// Si no hay datos del producto, redirigimos al catalogo
if (!$producto_id || !$producto_tipo) {
    header("Location: catalogo.php");
    exit;
}

// Obtenemos el nombre del producto según el tipo
$nombre_producto = "";
if ($producto_tipo === "libro") {
    $sql = "SELECT TITULO FROM Libros WHERE ID = $producto_id";
    $resultado = $conexion->query($sql);
    if ($resultado && $resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $nombre_producto = $producto['TITULO'];
    }
} else {
    $sql = "SELECT Titulo FROM peliculas WHERE ID = $producto_id";
    $resultado = $conexion->query($sql);
    if ($resultado && $resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $nombre_producto = $producto['Titulo'];
    }
}

// Obtenemos la lista de clientes ordenados por nombre
$clientes = [];
$sql_clientes = "SELECT ID, NOMBRE, APELLIDOS FROM clientes ORDER BY NOMBRE";
$resultado_clientes = $conexion->query($sql_clientes);
if ($resultado_clientes) {
    while ($cliente = $resultado_clientes->fetch_assoc()) {
        $clientes[] = $cliente;
    }
}

// Procesamos la reserva cuando se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'])) {
    $cliente_id = $_POST['cliente_id'];
    $producto_id_post = $_POST['producto_id'];
    $producto_tipo_post = $_POST['producto_tipo'];
    
    // Validamos que se haya seleccionado un cliente
    if (empty($cliente_id)) {
        $error = "Debe seleccionar un cliente";
    } else {
        // Determinamos en que tabla buscar según el tipo de producto
        $tabla = ($producto_tipo_post === "libro") ? "Libros" : "peliculas";
        // Verificamos que el producto este disponible
        $sql_check = "SELECT DISPONIBLE FROM $tabla WHERE ID = $producto_id_post";
        $resultado_check = $conexion->query($sql_check);
        
        if ($resultado_check && $resultado_check->num_rows > 0) {
            $prod = $resultado_check->fetch_assoc();
            
            // Verificamos disponibilidad
            if (!$prod['DISPONIBLE']) {
                $error = "Este producto ya no está disponible";
            } else {
                // Obtenemos la fecha actual para la reserva
                $fecha_reserva = date('Y-m-d');
                
                // Insertamos la reserva según el tipo de producto
                if ($producto_tipo_post === "libro") {
                    $sql_reserva = "INSERT INTO reserva (ID_LIBRO, CLIENTE_ID, FECHA_RESERVA, DEVUELTO) 
                                   VALUES ($producto_id_post, $cliente_id, '$fecha_reserva', FALSE)";
                } else {
                    $sql_reserva = "INSERT INTO reserva (ID_PELICULA, CLIENTE_ID, FECHA_RESERVA, DEVUELTO) 
                                   VALUES ($producto_id_post, $cliente_id, '$fecha_reserva', FALSE)";
                }
                
                if ($conexion->query($sql_reserva)) {
                    // Actualizamos la disponibilidad del producto a FALSE
                    $sql_update = "UPDATE $tabla SET DISPONIBLE = FALSE WHERE ID = $producto_id_post";
                    $conexion->query($sql_update);
                    
                    // Limpiamos el catálogo de la sesión para que se recargue actualizado
                    if ($producto_tipo_post === "libro") {
                        unset($_SESSION['libros_catalogo']);
                    } else {
                        unset($_SESSION['peliculas_catalogo']);
                    }
                    
                    // Limpiamos los datos de reserva de la sesión
                    unset($_SESSION['reserva_producto_id']);
                    unset($_SESSION['reserva_producto_tipo']);
                    
                    $mensaje = "¡Reserva realizada correctamente!";
                } else {
                    $error = "Error al realizar la reserva: " . $conexion->error;
                }
            }
        } else {
            $error = "Producto no encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $traducciones["reservarProducto"] ?? "Reservar Producto"; ?></title>
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
            --error: #ef4444;
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

        .wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
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
            margin: 20px 0;
        }
        
        .container h2 {
            color: var(--accent);
            margin-bottom: 24px;
            font-size: 1.5rem;
        }
        
        .producto-info {
            background: #f0f4f8;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            text-align: left;
        }
        
        .producto-info p {
            margin: 8px 0;
            font-weight: 600;
        }
        
        .mensaje {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .mensaje.exito {
            background: #d1fae5;
            color: var(--success);
        }
        
        .mensaje.error {
            background: #fee2e2;
            color: var(--error);
        }
        
        form {
            text-align: left;
        }
        
        label {
            display: block;
            margin-top: 14px;
            font-weight: 600;
            color: var(--text);
        }
        
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccd4e0;
            font-size: 1rem;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background: var(--success);
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 22px;
            padding: 12px;
            font-weight: 600;
            transition: 0.2s;
            width: 100%;
            border-radius: 6px;
        }
        
        input[type="submit"]:hover {
            opacity: 0.9;
        }
        
        .secondary-button {
            background: #6b7785;
            margin-top: 10px;
        }
        
        .secondary-button:hover {
            background: #5c6572;
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
    <h1><?php echo $traducciones["reservarProducto"] ?? "Reservar Producto"; ?></h1>
    <div class="lang-selector"> 
        <a href="?lang=es">Español</a> |
        <a href="?lang=en">English</a>
    </div>
</header>

<div class="wrapper">
    <div class="container">
        <h2><?php echo $traducciones["confirmarReserva"] ?? "Confirmar Reserva"; ?></h2>
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje exito"><?php echo $mensaje; ?></div>
            <form action="catalogo.php" method="GET">
                <input type="submit" value="<?php echo $traducciones['volverCatalogo'] ?? 'Volver al Catálogo'; ?>" style="background: var(--accent);">
            </form>
            <form action="fichero.php" method="GET">
                <input type="submit" value="<?php echo $traducciones['volverMenu'] ?? 'Volver al Menú Principal'; ?>" style="background: var(--accent); margin-top: 10px;">
            </form>
        <?php else: ?>
            
            <?php if (!empty($error)): ?>
                <div class="mensaje error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="producto-info">
                <p><strong><?php echo $traducciones["producto"] ?? "Producto"; ?>:</strong> <?php echo htmlspecialchars($nombre_producto); ?></p>
                <p><strong><?php echo $traducciones["productoTipo"] ?? "Tipo"; ?>:</strong> <?php echo ucfirst($producto_tipo); ?></p>
            </div>
            
            <form method="POST">
                <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                <input type="hidden" name="producto_tipo" value="<?php echo $producto_tipo; ?>">
                
                <label for="cliente_id"><?php echo $traducciones["seleccionarCliente"] ?? "Seleccionar Cliente"; ?>:</label>
                <select name="cliente_id" id="cliente_id" required>
                    <option value="">-- <?php echo $traducciones["seleccionarCliente"] ?? "Seleccionar cliente"; ?> --</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['ID']; ?>">
                            <?php echo htmlspecialchars($cliente['NOMBRE'] . ' ' . $cliente['APELLIDOS']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="<?php echo $traducciones['confirmarReserva'] ?? 'Confirmar Reserva'; ?>">
            </form>
            
            <form action="catalogo.php" method="GET">
                <input type="submit" class="secondary-button" value="<?php echo $traducciones['cancelar'] ?? 'Cancelar'; ?>">
            </form>
            
        <?php endif; ?>
    </div>
</div>

<footer>
    <?php echo $traducciones["language"] ?? "Idioma"; ?>
</footer>

</body>
</html>