<?php
// Iniciamos la sesin
session_start();
include "internacionalizacion.php";
include "conexion_db.php";

// Variables para los mensajes
$mensaje = "";
$error = "";

// Procesamos el registro cuando se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    
    // Obtenemos los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fechanacimiento = $_POST['fecha_nacimiento'];
    $localidad = $_POST['localidad'];
    
    // Validamos que todos los campos esten todos completos
    if (empty($nombre) || empty($apellidos) || empty($fechanacimiento) || empty($localidad)) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Insertamos el nuevo cliente en la base de datos
        $consulta = "INSERT INTO clientes (NOMBRE, APELLIDOS, FECHA_NACIMIENTO, LOCALIDAD) 
                VALUES ('$nombre', '$apellidos', '$fechanacimiento', '$localidad')";
        $resultado = $conexion->query($consulta);

        if ($resultado == TRUE) {
            $mensaje = "¡Cliente registrado correctamente!";
        } else {
            $error = "Error al registrar el cliente: " . $conexion->error;
        }
        
        $conexion->close();
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registro</title>
<style>
:root {
    --bg:#f5f7fb;
    --card:#ffffff;
    --accent:#276fbf;
    --accent-dark:#1f57a0;
    --muted:#6b7280;
    --radius:8px;
    --accent-light:#e6f0fa;
    --success: #10b981;
    --error: #ef4444;
}

body {
    margin:0;
    font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    background: linear-gradient(180deg, var(--bg), #eef4fb);
    color:#111827;
}

.login-wrapper {
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:2rem;
}

.login-card {
    width:100%;
    max-width:420px;
    background:var(--card);
    border-radius:var(--radius);
    padding:24px;
    box-shadow:0 8px 24px rgba(16,24,40,0.08);
    border:1px solid rgba(39,111,191,0.06);
    text-align:center;
}

h1 {
    margin:0 0 12px;
    font-size:1.6rem;
    color:var(--accent-dark);
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

label {
    display:block;
    margin-top:12px;
    font-size:0.9rem;
    color:var(--muted);
    text-align:left;
}

input[type="text"],
input[type="date"],
input[type="password"] {
    width:100%;
    padding:10px 12px;
    margin-top:6px;
    border:1px solid #e6e9ee;
    border-radius:6px;
    background:#fff;
    box-sizing:border-box;
    font-size:0.95rem;
}

input[type="submit"] {
    margin-top:18px;
    width:100%;
    padding:10px 12px;
    background:var(--accent);
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
    font-size:0.98rem;
    transition:0.2s;
}

input[type="submit"]:hover {
    background:var(--accent-dark);
}

.secondary-button {
    background: #6b7785;
    margin-top: 10px;
}

.secondary-button:hover {
    background: #5c6572;
}

.language-selector {
    display:flex;
    justify-content:center;
    gap:12px;
    margin-top:20px;
}

.lang-btn {
    display:flex;
    align-items:center;
    gap:6px;
    background:var(--accent-light);
    color:var(--accent);
    border:1px solid var(--accent);
    border-radius:20px;
    padding:6px 12px;
    font-size:0.9rem;
    font-weight:600;
    text-decoration:none;
    transition:all 0.25s ease;
}

.lang-btn:hover {
    background:var(--accent);
    color:#fff;
    transform:translateY(-1px);
}

.lang-btn.active {
    background:var(--accent);
    color:#fff;
}

.lang-btn span {
    font-size:1.1rem;
}
</style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <h1><?php echo $traducciones["registro"]; ?></h1>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje exito"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="mensaje error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="nombre"><?php echo $traducciones["nombre"]; ?></label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellidos"><?php echo $traducciones["apellidos"]; ?></label>
            <input type="text" id="apellidos" name="apellidos" required>

            <label for="fecha_nacimiento"><?php echo $traducciones["fechanacimiento"]; ?></label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>

            <label for="localidad"><?php echo $traducciones["localidad"]; ?></label>
            <input type="text" id="localidad" name="localidad" required>

            <input type="submit" value="<?php echo $traducciones["registro"]; ?>">
        </form>
        
        <form action="fichero.php" method="GET">
            <input type="submit" class="secondary-button" value="<?php echo $traducciones["volver"]; ?>">
        </form>

        <div class="language-selector">
            <a href="?lang=es" class="lang-btn <?php echo ($_SESSION['lang'] ?? 'es') === 'es' ? 'active' : ''; ?>">
                <span>🇪🇸</span> Español
            </a>
            <a href="?lang=en" class="lang-btn <?php echo ($_SESSION['lang'] ?? 'es') === 'en' ? 'active' : ''; ?>">
                <span>🇬🇧</span> English
            </a>
        </div>
    </div>
</div>
</body>
</html>