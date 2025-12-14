<?php
// Iniciamos la sesion
session_start();

include "conexion_db.php";
include "internacionalizacion.php";

// Verificamos si el usuario ha iniciado sesion
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Variables para los mensajes
$mensaje = "";
$error = "";

// Procesamos el formulario cuando se envia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    
    // Obtenemos los datos del formulario
    $titulo = $_POST['titulo'];
    $anio = $_POST['anio'];
    $director = $_POST['director'];
    $actores = $_POST['actores'];
    $genero = $_POST['genero'];
    $tipo_adaptacion = $_POST['tipo_adaptacion'] ?? 'Película'; // Valor por defecto pelicula
    
    // Validamos que todos los campos estan completos
    if (empty($titulo) || empty($anio) || empty($director) || empty($actores) || empty($genero)) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Convertimos el año a formato DATE (YYYY-MM-DD)
        $anio_fecha = $anio . "-01-01";
        // Insertamos la película o serie en la base de datos
        $consulta = "INSERT INTO peliculas (Titulo, AÑO_ESTRENO, DIRECTOR, ACTORES, GENERO, TIPO_ADAPTACION) 
                VALUES ('$titulo', '$anio_fecha', '$director', '$actores', '$genero', '$tipo_adaptacion')";
        $resultado = $conexion->query($consulta);

        if ($resultado == TRUE) {
            $mensaje = "¡Película insertada correctamente!";
            
            // Limpiamos el catálogo de películas para que se recargue actualizado
            unset($_SESSION['peliculas_catalogo']);
        } else {
            $error = "Error al insertar la película: " . $conexion->error;
        }
        
        $conexion->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $traducciones["nuevaPelicula"]; ?></title>
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
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccd4e0;
            font-size: 1rem;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background: var(--accent);
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 22px;
            padding: 12px;
            font-weight: 600;
            transition: 0.2s;
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
        .language-selector {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
        }
        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #e6f0fa;
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .lang-btn:hover {
            background: var(--accent);
            color: #fff;
            transform: translateY(-1px);
        }
        .lang-btn.active {
            background: var(--accent);
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo $traducciones["nuevaPeliculaSerie"]; ?></h1>
        
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje exito"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="mensaje error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="nueva_pelicula.php" method="POST">
            <label for="tipo_adaptacion"><?php echo $traducciones["tipo"]; ?></label>
            <select id="tipo_adaptacion" name="tipo_adaptacion" required>
                <option value="Película"><?php echo $traducciones["pelicula"]; ?></option>
                <option value="Serie"><?php echo $traducciones["serie"]; ?></option>
            </select>

            <label for="titulo"><?php echo $traducciones["titulo"]; ?>:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="anio"><?php echo $traducciones["año"]; ?>:</label>
            <input type="number" id="anio" name="anio" min="1900" max="2100" required>

            <label for="director"><?php echo $traducciones["director"]; ?>:</label>
            <input type="text" id="director" name="director" required>

            <label for="actores"><?php echo $traducciones["actores"]; ?>:</label>
            <input type="text" id="actores" name="actores"  required>

            <label for="genero"><?php echo $traducciones["genero"]; ?>:</label>
            <select id="genero" name="genero" required>
                <option value=""><?php echo $traducciones["--seleccionar--"]; ?></option>
                <option value="Ciencia ficción"><?php echo $traducciones["cienciaFiccion"]; ?></option>
                <option value="Drama"><?php echo $traducciones["drama"]; ?></option>
                <option value="Romance"><?php echo $traducciones["romance"]; ?></option>
                <option value="Biografía"><?php echo $traducciones["biografia"]; ?></option>
                <option value="Thriller">Thriller</option>
                <option value="Fantasía"><?php echo $traducciones["fantasia"]; ?></option>
            </select>

            <input type="submit" value="<?php echo $traducciones["guardarPeliculaSerie"]; ?>">
        </form>

        <form action="fichero.php" method="GET">
            <input type="submit" class="secondary-button" value="<?php echo $traducciones["volver"]; ?>">
        </form>
    </div>

    <div class="container">
        <div class="language-selector">
            <a href="?lang=es" class="lang-btn <?php echo ($_SESSION['lang'] ?? 'es') === 'es' ? 'active' : ''; ?>">
                <span>🇪🇸</span> Español
            </a>
            <a href="?lang=en" class="lang-btn <?php echo ($_SESSION['lang'] ?? 'es') === 'en' ? 'active' : ''; ?>">
                <span>🇬🇧</span> English
            </a>
        </div>
    </div>
</body>
</html>


