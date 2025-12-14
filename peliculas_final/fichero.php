<?php
session_start();
include "internacionalizacion.php";
$producto_tipo = $_SESSION['producto_tipo'] ?? "pelicula";
$genero = $_SESSION['genero'] ?? "";
$anio = $_SESSION['anio'] ?? "";
$director = $_SESSION['director'] ?? "";
$titulo = $_SESSION['titulo'] ?? "";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Filtros</title>
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #1f3b4d;
            --muted: #6b7785;
            --accent: #2b7cc3;
            --accent-light: #e6f0fa;
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
            height: 100vh;
        }
        .container {
            background: var(--card);
            padding: 32px 48px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(35, 47, 63, 0.1);
            text-align: center;
            width: 400px;
            margin-bottom: 20px;
        }
        h1 {
            color: var(--accent);
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-top: 12px;
            text-align: left;
            font-weight: 600;
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
            margin-top: 20px;
            transition: 0.2s;
            border-radius: 6px;
            font-weight: 600;
        }
        input[type="submit"]:hover {
            opacity: 0.9;
        }

        .language-selector {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 8px;
        }

        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
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

        .lang-btn span {
            font-size: 1.1rem;
        }

        p {
            color: var(--muted);
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo $traducciones["tituloFiltra"]; ?></h1>
    <form action="catalogo.php" method="POST">
        <label for="producto_tipo"><?php echo $traducciones["productoTipo"]; ?></label>
        <select name="producto_tipo" id="producto_tipo">
            <option value="pelicula" <?php echo ($producto_tipo == "pelicula") ? "selected" : ""; ?>>Película</option>
            <option value="serie" <?php echo ($producto_tipo == "serie") ? "selected" : ""; ?>>Serie</option>
            <option value="libro" <?php echo ($producto_tipo == "libro") ? "selected" : ""; ?>>Libro</option>
        </select>

        <label for="titulo"><?php echo $traducciones["tituloFiltrar"] ?>:</label>
        <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($titulo); ?>">

        <label for="genero"><?php echo $traducciones["generoFiltrar"]; ?></label>
        <select name="genero" id="genero">
            <option value=""><?php echo $traducciones["seleccionGenero"]; ?></option>
            <option value="Ciencia ficción" <?php echo ($genero == "Ciencia ficción") ? "selected": "" ?>>Ciencia ficción</option>
            <option value="Drama" <?php echo ($genero == "Drama") ? "selected": "" ?>>Drama</option>
            <option value="Romance" <?php echo ($genero == "Romance") ? "selected": "" ?>>Romance</option>
            <option value="Biografía" <?php echo ($genero == "Biografía") ? "selected": "" ?>>Biografía</option>
            <option value="Thriller" <?php echo ($genero == "Thriller") ? "selected": "" ?>>Thriller</option>
            <option value="Fantasía" <?php echo ($genero == "Fantasía") ? "selected": "" ?>>Fantasía</option>
        </select>

        <label for="anio"><?php echo $traducciones["añoFiltrar"]; ?></label>
        <input type="number" name="anio" id="anio" value="<?php echo htmlspecialchars($anio); ?>">

        <label for="director"><?php echo $traducciones["directorAutorFiltrar"]; ?>:</label>
        <input type="text" name="director" id="director" value="<?php echo htmlspecialchars($director); ?>">

        <input type="submit" value="<?php echo $traducciones["aplicarFiltros"]; ?>">
    </form>

    <form action="nuevo_libro.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["añadirLibro"]; ?>">
    </form>

    <form action="nueva_pelicula.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["añadirPelicula"]; ?>">
    </form>

    <form action="registro_cliente.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["añadirCliente"]; ?>">
    </form>

    <form action="reservas.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["verReservas"]; ?>">
    </form>

    <form action="logout.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["cerrarSesion"]; ?>">
    </form>
</div>

<div class="container">
    <p><?php echo $traducciones["language"]; ?></p>
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