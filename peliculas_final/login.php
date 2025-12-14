<?php
session_start();
include "internacionalizacion.php";
include "conexion_db.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = $traducciones["error"] ?? "Usuario o contraseña incorrectos";
    } else {
        $sql = "SELECT * FROM Usuarios WHERE Usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            $password_hash = hash('sha256', $password);
            
            if ($password_hash === $usuario['Contraseña']) {
                $_SESSION['username'] = $usuario['Usuario'];
                $_SESSION['user_id'] = $usuario['ID'];
                header('Location: fichero.php');
                exit();
            } else {
                $error = $traducciones["error"] ?? "Usuario o contraseña incorrectos";
            }
        } else {
            $error = $traducciones["error"] ?? "Usuario o contraseña incorrectos";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $traducciones["titulo"]; ?></title>
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #1f3b4d;
            --muted: #6b7785;
            --accent: #2b7cc3;
            --error: #dc2626;
        }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: var(--card);
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(35, 47, 63, 0.1);
            text-align: center;
            width: 350px;
        }
        h1 {
            color: var(--accent);
            margin-bottom: 24px;
        }
        .error {
            background: #fee;
            color: var(--error);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        label {
            display: block;
            margin-top: 16px;
            text-align: left;
            font-weight: 600;
        }
        input {
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
            font-weight: 600;
        }
        input[type="submit"]:hover {
            opacity: 0.9;
        }
        .lang-selector {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .lang-selector a {
            color: var(--accent);
            text-decoration: none;
            margin: 0 8px;
            font-weight: 600;
        }
        .lang-selector a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo $traducciones["titulo"]; ?></h1>
    
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label for="username"><?php echo $traducciones["usuario"]; ?>:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="password"><?php echo $traducciones["contraseña"]; ?>:</label>
        <input type="password" name="password" id="password" required>
        
        <input type="submit" value="<?php echo $traducciones["acceder"]; ?>">
    </form>
    
    <div class="lang-selector">
        <a href="?lang=es">Español</a> |
        <a href="?lang=en">English</a>
    </div>
</div>

</body>
</html>