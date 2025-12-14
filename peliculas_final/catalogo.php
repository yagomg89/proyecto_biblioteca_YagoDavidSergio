<?php
// Iniciamos la sesión para poder guardar y leer datos del usuario
session_start();

// Incluimos archivos externos que necesitamos
include "internacionalizacion.php";  // Para el sistema de idiomas
include "conexion_db.php";            // Para conectar con la base de datos
include "obtener_peliculas.php";      // Funciones para obtener peliculas de la BD
include "obtener_libros.php";         // Funciones para obtener libros de la BD
//include "traits.php";                 // Traits para reutilizar código

// Verificamos si el usuario ha iniciado sesion
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  
    exit;  
}

// Si el formulario fue enviado por POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardamos los filtros en la sesion para que persistan al cambiar de pagina
    $_SESSION['producto_tipo'] = $_POST['producto_tipo'] ?? "pelicula";
    $_SESSION['genero'] = $_POST['genero'] ?? "";
    $_SESSION['anio'] = $_POST['anio'] ?? "";
    $_SESSION['director'] = $_POST['director'] ?? "";
    $_SESSION['titulo'] = $_POST['titulo'] ?? "";
}

// Leemos los filtros desde la sesion 
$producto_tipo = $_SESSION['producto_tipo'] ?? "pelicula"; // Valor por defecto: pelicula
$genero_filtro = $_SESSION['genero'] ?? "";
$anio_filtro = $_SESSION['anio'] ?? "";
$director_filtro = $_SESSION['director'] ?? "";
$titulo_filtro = $_SESSION['titulo'] ?? "";

class Producto {
    //use FormatoFecha;
    public $titulo;
    public $genero;
    public $anio;
    public $disponible;
    public $id;

    public function __construct($titulo, $genero, $anio, $disponible, $id = null) {
        $this->titulo = $titulo;          
        $this->genero = $genero;
        $this->anio = $anio;
        $this->disponible = $disponible;
        $this->id = $id;
    }

    public function mostrarInfo() {
        return "<h2>" . $this->titulo . " (" . $this->anio . ")</h2>
                <p><strong>Género:</strong> " . $this->genero . "</p>
                <p><strong>Disponible:</strong> " . ($this->disponible ? "Sí" : "No") . "</p>";
    }
}


class pelicula extends Producto {
    public $director;
    public $actores;

    public function __construct($titulo, $director, $actores, $anio, $genero, $disponible, $id = null){
       
        Producto::__construct($titulo, $genero, $anio, $disponible, $id);
        
        $this->director = $director;
        $this->actores = $actores;
    }

    
    public function mostrarInfo(){
        return Producto::mostrarInfo() . 
               "<p><strong>Director:</strong> " . $this->director . "</p>" .
               "<p><strong>Actores:</strong> " . $this->actores . "</p>";
    }
}


class serie extends pelicula{
    public $temporadas;

    public function __construct($titulo, $director, $actores, $anio, $genero, $disponible, $temporadas, $id = null){
        pelicula::__construct($titulo, $director, $actores, $anio, $genero, $disponible, $id);
        $this->temporadas = $temporadas;
    }

    public function mostrarInfo(){
        return pelicula::mostrarInfo() . 
               "<p><strong>Temporadas:</strong> " . $this->temporadas . "</p>";
    }
}


class Libro extends Producto {
    public $autor_id;
    public $autor_nombre;  
    public $editorial;
    public $paginas;
    public $precio;

    public function __construct($titulo, $autor_id, $genero, $editorial, $paginas, $año_publicacion, $precio, $disponible, $autor_nombre = "", $id = null) {
        Producto::__construct($titulo, $genero, $año_publicacion, $disponible, $id);
        
        $this->autor_id = $autor_id;
        $this->autor_nombre = $autor_nombre;  
        $this->editorial = $editorial;
        $this->paginas = $paginas;
        $this->precio = $precio;
    }

    public function mostrarInfo() {
        // Si tenemos el nombre del autor, mostrarlo; si no, mostrar el ID
        $autor_display = !empty($this->autor_nombre) ? $this->autor_nombre : "ID: " . $this->autor_id;
        
        return Producto::mostrarInfo() .
               "<p><strong>Autor:</strong> " . $autor_display . "</p>" .
               "<p><strong>Editorial:</strong> " . $this->editorial . "</p>" .
               "<p><strong>Páginas:</strong> " . $this->paginas . "</p>" .
               "<p><strong>Precio:</strong> $" . number_format($this->precio, 2) . "</p>";
    }
}



//Carga de datos desde la base de datos si no están en sesión

// Cargamos películas y series desde la base de datos si no están en sesion
if (!isset($_SESSION['peliculas_catalogo'])) {
    // Obtenemos los datos de la base de datos
    $datos = obtenerPeliculas($conexion);
    
    // Creamos un array vacío para guardar los objetos
    $_SESSION['peliculas_catalogo'] = [];

    // Recorremos cada fila de datos
    foreach ($datos as $p) {
        // Extraemos solo el año (primeros 4 caracteres) de una fecha como "1999-05-21"
        $anio = intval(substr($p["AÑO_ESTRENO"], 0, 4));

        // Leemos si está disponible (si existe en BD, sino asumimos true)
        $disponible = isset($p["DISPONIBLE"]) ? (bool)$p["DISPONIBLE"] : true;

        // Dependiendo del tipo, creamos un objeto película o serie
        switch ($p["TIPO_ADAPTACION"]) {
            case "Película":
                // Creamos un objeto película y lo añadimos al array
                $_SESSION['peliculas_catalogo'][] = new pelicula(
                    $p["Titulo"],
                    $p["DIRECTOR"],
                    $p["ACTORES"],
                    $anio,
                    $p["GENERO"],
                    $disponible,
                    $p["ID"]
                );
                break;

            case "Serie":
                // Creamos un objeto serie y lo añadimos al array
                $_SESSION['peliculas_catalogo'][] = new serie(
                    $p["Titulo"],
                    $p["DIRECTOR"],
                    $p["ACTORES"],
                    $anio,
                    $p["GENERO"],
                    $disponible,
                    1,  // Temporadas 
                    $p["ID"]
                );
                break;
        }
    }
}

// Cargamos libros desde la base de datos si no están en sesión

if (!isset($_SESSION['libros_catalogo'])) {
    $datosLibros = obtenerLibros($conexion);
    $_SESSION['libros_catalogo'] = [];

    foreach ($datosLibros as $l) {
        // Extraemos el año, verificando que existe
        $anio = isset($l["AÑO"]) && !empty($l["AÑO"]) 
                ? intval(substr($l["AÑO"], 0, 4)) 
                : 0;
        
        // Obtenemos el nombre del autor consultando la tabla autores
        $autor_nombre = "";
        if (isset($l["AUTOR_ID"])) {
            // Hacemos una consulta SQL para buscar el nombre del autor
            $sql_autor = "SELECT NOMBRE FROM autores WHERE ID = " . $l["AUTOR_ID"];
            $resultado_autor = $conexion->query($sql_autor);
            
            // Si encontramos el autor
            if ($resultado_autor && $resultado_autor->num_rows > 0) {
                $autor_data = $resultado_autor->fetch_assoc();
                $autor_nombre = $autor_data['NOMBRE'];
            }
        }

        // Leemos disponibilidad
        $disponible = isset($l["DISPONIBLE"]) ? (bool)$l["DISPONIBLE"] : true;
        
        // Creamos el objeto Libro y lo añadimos al array
        $_SESSION['libros_catalogo'][] = new Libro(
            $l["TITULO"],
            $l["AUTOR_ID"],
            $l["GENERO"],
            $l["EDITORIAL"],
            $l["PAGINAS"],
            $anio,
            $l["PRECIO"],
            $disponible,
            $autor_nombre,
            $l["ID"]
        );
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Catálogo de películas filtrado</title>
    <style>
        :root {
            --bg: #e8f0fa;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --shadow: rgba(0, 0, 0, 0.08);
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

        .pelicula {
            background: var(--card);
            border-radius: 10px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px var(--shadow);
            margin-bottom: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .pelicula:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px var(--shadow);
        }

        h2 {
            margin: 0 0 8px 0;
            font-size: 1.25rem;
            color: var(--accent);
        }

        p {
            margin: 6px 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .no-results {
            color: #dc2626;
            font-weight: 600;
            margin: 16px 0;
            text-align: center;
        }

        .botones {
            text-align: center;
            margin-top: 20px;
        }

        a.boton, input[type="submit"] {
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
        }

        a.boton:hover, input[type="submit"]:hover {
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
    <h1><?php 
    if($producto_tipo === "libro"){
        echo $traducciones["tituloCatalogoLibros"];
    } else {
        echo $traducciones["tituloCatalogoPeliculasYseries"];
    } 
    ?></h1>
    <div class="lang-selector"> 
        <a href="?lang=es">Español</a> |
        <a href="?lang=en">English</a>
    </div>
</header>

<div class="container">
    <?php
        // Contador para saber cuántos resultados encontramos
        $contador_resultados = 0;

        // Recorremos los productos según el tipo seleccionado
        
        if ($producto_tipo === "libro") {
            // Recorremos todos los libros del catálogo
            foreach ($_SESSION['libros_catalogo'] ?? [] as $libro) {
                // Aplicamos los filtros: solo mostramos si cumple TODAS las condiciones
                if (($genero_filtro === "" || $libro->genero === $genero_filtro) &&
                    ($anio_filtro === "" || $libro->anio == $anio_filtro) &&
                    ($director_filtro === "" || stripos($libro->autor_nombre, $director_filtro) !== false) &&
                    ($titulo_filtro === "" || stripos($libro->titulo, $titulo_filtro) !== false)) {
                    
                    // Mostramos el libro
                    echo "<div class='pelicula'>";
                    echo $libro->mostrarInfo();  // Llamamos al método del objeto
                    
                    // Mostramos el boton de reservar si esta disponible
                    if ($libro->disponible) {
                        echo "<form action='reservar.php' method='POST' style='margin-top:12px;'>";
                        echo "<input type='hidden' name='producto_id' value='" . $libro->id . "'>";
                        echo "<input type='hidden' name='producto_tipo' value='libro'>";
                        echo "<input type='submit' value='Reservar' style='background:#10b981; color:#fff; padding:10px 18px; border:none; border-radius:8px; cursor:pointer; font-weight:600;'>";
                        echo "</form>";
                    }
                    
                    echo "</div>";
                    $contador_resultados++;  // Aumentamos el contador
                }
            }
        } 
        
        else {
            foreach ($_SESSION['peliculas_catalogo'] ?? [] as $pelicula) {
                // Verificamos que sea del tipo correcto 
                $es_tipo_correcto = true;
                
                // Si buscamos películas pero el objeto es una serie, no es correcto
                if ($producto_tipo === "pelicula" && $pelicula instanceof serie) {
                    $es_tipo_correcto = false;
                } 
                // Si buscamos series pero el objeto NO es una serie, no es correcto
                elseif ($producto_tipo === "serie" && !($pelicula instanceof serie)) {
                    $es_tipo_correcto = false;
                }

                // Aplicamos todos los filtros
                if ($es_tipo_correcto &&
                    ($genero_filtro === "" || $pelicula->genero === $genero_filtro) &&
                    ($anio_filtro === "" || $pelicula->anio == $anio_filtro) &&
                    ($director_filtro === "" || stripos($pelicula->director, $director_filtro) !== false) &&
                    ($titulo_filtro === "" || stripos($pelicula->titulo, $titulo_filtro) !== false)) {
                    
                    echo "<div class='pelicula'>";
                    echo $pelicula->mostrarInfo();
                    
                    // Boton de reservar si esta disponible
                    if ($pelicula->disponible) {
                        echo "<form action='reservar.php' method='POST' style='margin-top:12px;'>";
                        echo "<input type='hidden' name='producto_id' value='" . $pelicula->id . "'>";
                        echo "<input type='hidden' name='producto_tipo' value='pelicula'>";
                        echo "<input type='submit' value='". $traducciones["reservar"]."' style='background:#10b981; color:#fff; padding:10px 18px; border:none; border-radius:8px; cursor:pointer; font-weight:600;'>";
                        echo "</form>";
                    }
                    
                    echo "</div>";
                    $contador_resultados++;
                }
            }
        }
    ?>

    <?php 
    // Mostramos mensaje si no se encontraron resultados
    if ($contador_resultados === 0): 
    ?>
        <p class="no-results"><?php echo $traducciones["peliculasNoEncontradas"]; ?></p>
    <?php endif; ?>

    <!-- Mostramos cuántos productos hay en total -->
    <p><strong>
        <?php 
        if ($producto_tipo === "libro") {
            echo "Libros en catálogo: " . count($_SESSION['libros_catalogo']);
        } else {
            echo "Películas/Series en catálogo: " . count($_SESSION['peliculas_catalogo']);
        }
        ?>
    </strong></p>

    <!-- Botones de navegación -->
    <div class="botones">
        <a href="fichero.php" class="boton"><?php echo $traducciones["volverFormulario"]; ?></a>
        <form action="logout.php" method="POST" style="display:inline;">
            <input type="submit" value="<?php echo $traducciones["cerrarSesion"]; ?>">
        </form>
    </div>
</div>

<footer>
    <?php echo $traducciones["language"]; ?>
</footer>

</body>
</html>