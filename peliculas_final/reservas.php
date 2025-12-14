<?php
session_start();

include "internacionalizacion.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
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

    <h1><?php echo $traducciones["reservas"]; ?></h1>

    <form action="reservas_pelis.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["pelisyseries"]; ?>">
    </form>

    <form action="reservas_libros.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["libros"]; ?>">
    </form>

    <form action="historial_reservas.php" method="POST">
        <input type="submit" value="<?php echo $traducciones["historialReservass"]; ?>">
    </form>

    <form action="fichero.php" method="GET">
            <input type="submit" class="secondary-button" value="<?php echo $traducciones["volver"]; ?>">
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