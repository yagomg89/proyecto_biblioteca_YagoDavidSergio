-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-12-2025 a las 19:15:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_peliculas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autores`
--

CREATE TABLE `autores` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(255) NOT NULL,
  `FECHA_NACIMIENTO` date DEFAULT NULL,
  `LUGAR` varchar(255) NOT NULL,
  `FECHA_DEFUNCION` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `autores`
--

INSERT INTO `autores` (`ID`, `NOMBRE`, `FECHA_NACIMIENTO`, `LUGAR`, `FECHA_DEFUNCION`) VALUES
(1, 'J. R. R. Tolkien', '1892-01-03', 'Bloemfontein', '1973-09-02'),
(2, 'Ernest Hemingway', '1899-07-21', 'Oak Park', '1961-07-02'),
(3, 'C. S. Lewis', '1898-11-29', 'Belfast', '1963-11-22'),
(4, 'Susan E. Hinton', '1948-07-22', 'Tulsa', NULL),
(5, 'J. K. Rowling', '1965-07-31', 'Yate', NULL),
(6, 'George R. R. Martin', '1948-09-20', 'Bayonne', NULL),
(7, 'Fred Uhlman', '1901-01-19', 'Stuttgart', '1985-04-11'),
(8, 'Joël Dicker', '1985-06-16', 'Ginebra', NULL),
(9, 'Mary Ann Shaffer', '1934-12-13', 'Martinsburg', '2008-02-16'),
(10, 'Patricia García-Rojo', '1984-09-24', 'Jaén', NULL),
(11, 'Mark Haddon', '1962-10-28', 'Northampton', NULL),
(12, 'Berlie Doherty', '1943-11-06', 'Knotty Ash', NULL),
(13, 'Jane Austen', '1775-12-16', 'Steventon', '1817-07-18'),
(14, 'Mitch Albom', '1958-05-23', 'Passaic', NULL),
(15, 'David Lozano', '1974-10-30', 'Zaragoza', NULL),
(16, 'María Menéndez-Ponte', '1962-01-01', 'Coruña', NULL),
(17, 'Gabriel García Márquez', '1927-03-06', 'Aracataca', '2014-04-17'),
(18, 'Patrick Rothfuss', '1973-06-06', 'Madison', NULL),
(19, 'Michael Ende', '1929-11-12', 'Garmisch-Partenkirchen', '1995-08-28'),
(20, 'Brandon Sanderson', '1975-12-19', 'Lincoln', NULL),
(21, 'Philip K. Dick', '1928-12-16', 'Illinois', '1982-03-02'),
(22, 'Carlos Ruiz Zafón', '1964-09-25', 'Barcelona', '2020-06-19'),
(23, 'Laura Gallego', '1977-10-11', 'Cuart de Poblet', NULL),
(24, 'R. L. Stevenson', '1850-11-13', 'Edimburgo', '1894-12-03'),
(25, 'Roald Dahl', '1916-09-13', 'Llandaff', '1990-11-23'),
(26, 'Scott Fitzgerald', '1986-09-26', 'Minnesota', '1940-12-21'),
(27, 'Ray Bradbury ', '1920-08-22', 'Illinois', '2012-06-05'),
(28, 'Diego', NULL, '', NULL),
(29, 'No lo se', NULL, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(255) NOT NULL,
  `APELLIDOS` varchar(255) NOT NULL,
  `FECHA_NACIMIENTO` date NOT NULL,
  `LOCALIDAD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`ID`, `NOMBRE`, `APELLIDOS`, `FECHA_NACIMIENTO`, `LOCALIDAD`) VALUES
(1, 'Pedro', 'Díaz', '1990-01-01', 'Gijón'),
(2, 'Guillermo', 'Rosas', '1985-03-01', 'Gijón'),
(3, 'Martina', 'Martínez', '1984-07-25', 'Avilés'),
(4, 'Francisco', 'Villalba', '1996-03-02', 'Oviedo'),
(5, 'Lorena', 'López', '1997-04-15', 'Langreo'),
(6, 'Fernanda', 'Fernández', '1992-02-15', 'Mieres'),
(7, 'Roberto', 'Ibáñez', '1990-08-31', 'Grado'),
(8, 'Alejandra', 'Álvarez', '2006-06-06', 'Oviedo'),
(9, 'Marcos', 'Llorente', '2001-01-02', 'Grado'),
(10, 'Jorge', 'Molina', '1900-01-05', 'Gijón'),
(11, 'Luis', 'Hernández', '1985-05-05', 'Gijón'),
(12, 'Fernando', 'Torres', '2003-02-23', 'Avilés'),
(13, 'Santiago', 'Arias', '1986-06-16', 'Oviedo'),
(14, 'Rodrigo', 'Moreno', '1990-02-14', 'Oviedo'),
(15, 'Manuel', 'García', '1980-03-30', 'Oviedo'),
(16, 'Ángela', 'Sánchez', '1973-09-11', 'Mieres'),
(17, 'Lucía', 'López', '1985-12-25', 'Grado'),
(18, 'Míriam', 'Fernández', '1986-12-31', 'Avilés'),
(19, 'Daniel', 'Menéndez', '1980-08-08', 'Avilés'),
(20, 'Juan', 'Guzmán', '1990-04-23', 'Grado'),
(21, 'Mitis', 'Perez', '1678-11-11', 'Tineo'),
(22, 'Gonzalo', 'Asete', '2000-01-25', 'Cuenca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `ID` int(11) NOT NULL,
  `TITULO` varchar(255) NOT NULL,
  `AUTOR_ID` int(11) NOT NULL,
  `GENERO` varchar(255) NOT NULL,
  `EDITORIAL` varchar(255) NOT NULL,
  `PAGINAS` int(11) NOT NULL,
  `AÑO` date NOT NULL,
  `PRECIO` double NOT NULL,
  `DISPONIBLE` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`ID`, `TITULO`, `AUTOR_ID`, `GENERO`, `EDITORIAL`, `PAGINAS`, `AÑO`, `PRECIO`, `DISPONIBLE`) VALUES
(1, 'El Señor de los anillos: La comunidad del anillo', 1, 'Fantástico', 'Minotauro', 488, '1954-01-01', 18, 1),
(2, 'El viejo y el mar', 2, 'Novela', 'Debolsillo', 208, '1952-01-01', 10.95, 1),
(3, 'Las Crónicas de Narnia: El león, la bruja y el armario', 3, 'Fantástico', 'Destino', 240, '1950-01-01', 15, 1),
(4, 'Rebeldes', 4, 'Drama', 'Alfaguara', 224, '1967-01-01', 12, 1),
(5, 'Harry Potter y la prisionero de Azkaban', 5, 'Fantástico', 'Salamandra', 264, '1999-01-01', 18, 1),
(6, 'Canción de hielo y fuego: Juego de Tronos', 6, 'Fantástico', 'Planeta', 800, '1996-01-01', 20, 1),
(7, 'Reencuentro', 7, 'Drama', 'Tusquets', 128, '1971-01-01', 10, 1),
(8, 'La verdad sobre el caso Harry Quebert', 8, 'Policíaco', 'Alfaguara', 672, '2012-01-01', 12.95, 1),
(9, 'La sociedad literaria y el pastel de piel de patata de Guernsey', 9, 'Novela epistolar', 'Salamandra', 274, '2007-01-01', 10, 1),
(10, 'El mar', 10, 'Fantástico', 'SM', 260, '2015-01-01', 12.95, 1),
(11, 'El curioso incidente del perro a medianoche', 11, 'Novela', 'Salamandra', 270, '2003-01-01', 10, 1),
(12, 'La hija del mar', 12, 'Fantástico', 'SM', 112, '1996-01-01', 10, 1),
(13, 'Orgullo y prejuicio', 13, 'Novela', 'Penguin', 448, '1813-01-01', 12, 1),
(14, 'Martes con mi viejo profesor', 14, 'Novela biográfica', 'Maeva', 143, '1997-01-01', 13, 1),
(15, 'Desconocidos', 15, 'Policíaco', 'Edebé', 221, '2018-01-01', 12, 1),
(16, 'Nunca seré tu héroe', 16, 'Novela', 'SM', 192, '1998-01-01', 10.95, 1),
(17, 'Crónica de una muerte anunciada', 17, 'Policíaco', 'Debolsillo', 156, '1981-01-01', 9.95, 1),
(18, 'El nombre del viento', 18, 'Fantástico', 'Debolsillo', 880, '2007-01-01', 22, 1),
(19, 'La historia interminable', 19, 'Fantástico', 'Alfaguara', 496, '1979-01-01', 15, 1),
(20, 'La ley de la calle', 4, 'Drama', 'Alfaguara', 112, '1975-01-01', 10, 1),
(21, 'Nacidos de la bruma: El imperio final', 20, 'Fantástico', 'Nova', 841, '2006-01-01', 20, 1),
(22, '¿Sueñan los androides con ovejas eléctricas?', 21, 'Ciencia ficción', 'Minotauro', 272, '1968-01-01', 10, 1),
(23, 'El príncipe de la niebla', 22, 'Fantástico', 'Edebé', 240, '1993-01-01', 14, 1),
(24, 'La leyenda del rey errante', 23, 'Fantástico', 'SM', 560, '2004-01-01', 21, 1),
(25, 'La isla del tesoro', 24, 'Aventuras', 'Edelvives', 288, '1883-01-01', 24.9, 1),
(26, 'Matilda', 25, 'Infantil', 'Loqueleo', 288, '1988-01-01', 10, 1),
(27, 'El gran Gatsby', 26, 'Drama', 'Austral', 224, '1925-01-01', 11.5, 1),
(28, 'Fahrenheit 451', 27, 'Ciencia ficción', 'Debolsillo', 192, '1953-01-01', 12.5, 1),
(29, 'Los vergadores', 28, 'Romance', 'Santillana', 34, '1986-01-01', 21, 1),
(30, 'El diario de Greg', 29, 'Fantasía', 'Santillana', 197, '2008-01-01', 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `ID` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `AÑO_ESTRENO` date NOT NULL,
  `DIRECTOR` varchar(255) NOT NULL,
  `ACTORES` varchar(255) NOT NULL,
  `GENERO` varchar(255) NOT NULL,
  `TIPO_ADAPTACION` varchar(255) NOT NULL,
  `ADAPTACION_ID` int(11) DEFAULT NULL,
  `DISPONIBLE` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`ID`, `Titulo`, `AÑO_ESTRENO`, `DIRECTOR`, `ACTORES`, `GENERO`, `TIPO_ADAPTACION`, `ADAPTACION_ID`, `DISPONIBLE`) VALUES
(1, 'El editor de libros', '2016-01-01', 'Michael Grandage', 'Colin Firth, Jude Law, Nicole Kidman', 'Biografía', 'Película', NULL, 1),
(2, 'La historia interminable', '1984-01-01', 'Wolfgang Petersen', 'Barret Oliver, Noah Hathaway, Moses Gunn', 'Fantasía', 'Película', 19, 1),
(3, 'La ladrona de libros', '2013-01-01', 'Brian Percival', 'Sophie Nélisse, Geoffrey Rush, Emily Watson, Nico Liersch', 'Drama', 'Película', NULL, 1),
(4, 'La bruja novata', '1971-01-01', 'Robert Stevenson', 'Angela Lansbury, David Tomlinson, Roddy McDowall', 'Fantasía', 'Película', NULL, 0),
(5, 'Harry Potter y el prisionero de Azkaban', '2004-01-01', 'Alfonso Cuarón', 'Daniel Radcliffe, Rupert Grint, Emma Watson', 'Fantasía', 'Película', 5, 1),
(6, 'El señor de los anillos: La comunidad del anillo', '2001-01-01', 'Peter Jackson', 'Elijah Wood, Ian McKellen, Viggo Mortensen', 'Fantasía', 'Película', 1, 1),
(7, 'Charlie y la fábrica de chocolate', '2005-01-01', 'Tim Burton', 'Johnny Depp, Freddie Highmore, David Kelly, Deep Roy', 'Fantasía', 'Película', NULL, 1),
(8, 'Las Crónicas de Narnia: El león, la bruja y el armario', '2005-01-01', 'Andrew Adamson', 'Georgie Henley, William Moseley, Skandar Keynes, Anna Popplewell, Tilda Swinton', 'Fantasía', 'Película', NULL, 1),
(9, 'Rebeldes', '1983-01-01', 'Francis Ford Coppola', 'C. Thomas Howell, Matt Dillon, Ralph Macchio, Diane Lane, Rob Lowe, Patrick Swayze, Emilio Estévez, Tom Cruise', 'Drama', 'Película', 4, 1),
(10, 'Juego de Tronos: Temporada 1', '2011-01-01', 'David Benioff, D.B. Weiss', 'Emilia Clarke, Kit Harington, Lena Headey, Peter Dinklage, Maisie Williams, Nikolaj Coster-Waldau, Sophie Turner', 'Fantasía', 'Serie', 6, 1),
(11, 'La verdad sobre el caso Harry Quebert', '2018-01-01', 'Jean-Jacques Annaud', 'Patrick Dempsey, Ben Schnetzer, Kristine Froseth, Damon Wayans Jr.', 'Policíaco', 'Serie', 8, 1),
(12, 'La sociedad literaria y el pastel de piel de patata de Guernsey', '2018-01-01', 'Mike Newell', 'Lily James, Michiel Huisman, Glen Powell, Jessica Brown Findlay, Matthew Goode', 'Drama', 'Película', 9, 1),
(13, 'Orgullo y prejuicio', '2005-01-01', 'Joe Wright', 'Keira Knightley, Matthew Macfadyen, Brenda Blethyn, Donald Sutherland', 'Romance', 'Película', 13, 1),
(14, 'Orgullo y prejuicio', '1995-01-01', 'Simon Langton', 'Colin Firth, Jennifer Ehle, David Bamber, Crispin Bonham-carter, Anna Chancellor', 'Romance', 'Serie', 13, 1),
(15, 'Crónica de una muerte anunciada', '1987-01-01', 'Francesco Rosi', 'Anthony Delon, Rupert Everett, Lucía Bosé, Ornella Muti, Gian Maria Volonté', 'Drama', 'Película', NULL, 1),
(16, 'La ley de la calle', '1983-01-01', 'Francis Ford Coppola', 'Matt Dillon, Mickey Rourke, Diane Lane, Dennis Hopper, Nicolas Cage', 'Drama', 'Película', 20, 1),
(17, 'Blade Runner', '1982-01-01', 'Ridley Scott', 'Harrison Ford, Rutger Hauer, Sean Young, Daryl Hannah, Edward James Olmos', 'Ciencia ficción', 'Película', 22, 1),
(18, 'La isla del tesoro', '1934-01-01', 'Victor Fleming', 'Jackie Cooper, Wallace Beery, Lewis Stone, Lionel Barrymore, Otto Kruger', 'Aventuras', 'Película', 25, 1),
(19, 'La isla del tesoro', '1950-01-01', 'Byron Haskin', 'Bobby Driscoll, Robert Newton, Basil Sydney, Walter Fitzgerald, Denis O\'Dea', 'Aventuras', 'Película', 25, 1),
(20, 'La isla del tesoro', '1990-01-01', 'Fraser Clarke Heston', 'Charlton Heston, Christian Bale, Oliver Reed, Christopher Lee, Richard Johnson', 'Aventuras', 'Serie', 25, 1),
(21, 'Matilda', '1996-01-01', 'Danny DeVito', 'Mara Wilson, Danny DeVito, Rhea Perlman, Embeth Davidtz, Pam Ferris', 'Infantil', 'Película', NULL, 1),
(22, 'Un mundo de fantasía', '1971-01-01', 'Mel Stuart', 'Gene Wilder, Jack Albertson, Peter Ostrum, Roy Kinnear, Michael Bollner', 'Infantil', 'Película', NULL, 1),
(23, 'Por quién doblan las campanas', '1943-01-01', 'Sam Wood', 'Gary Cooper, Ingrid Bergman, Akim Tamiroff, Arturo de Córdova, Vladimir Sokoloff', 'Drama', 'Película', NULL, 1),
(24, 'Harry Potter y el cáliz de fuego', '2005-01-01', 'Mike Newell', 'Daniel Radcliffe, Rupert Grint, Emma Watson, Robbie Coltrane, Michael Gambon', 'Fantasía', 'Película', NULL, 1),
(25, 'El gran Gatsby', '1949-01-01', 'Elliott Nugent', 'Alan Ladd, Betty Field, Macdonald Carey, Ruth Hussey, Barry Sullivan', 'Drama', 'Película', 27, 1),
(26, 'El gran Gatsby', '1974-01-01', 'Jack Clayton', 'Robert Redford, Mia Farrow, Bruce Dern, Karen Black, Scott Wilson', 'Drama', 'Película', 27, 1),
(27, 'El gran Gatsby', '2000-01-01', 'Robert Markowitz', 'Mira Sorvino, Toby Stephens, Paul Rudd, Martin Donovan, Francie Swift', 'Drama', 'Serie', 27, 1),
(28, 'El gran Gatsby', '2013-01-01', 'Baz Luhrmann', 'Leonardo DiCaprio, Tobey Maguire, Carey Mulligan, Joel Edgerton, Isla Fisher', 'Drama', 'Película', 27, 1),
(29, 'Fahrenheit 451', '1966-01-01', 'François Truffaut', 'Julie Christie, Oskar Werner, Cyril Cusack, Anton Diffring, Jeremy Spenser, Ann Bell', 'Ciencia ficción', 'Película', 26, 1),
(30, 'Fahrenheit 451', '2018-01-01', 'Ramin Bahrani', 'Michael B. Jordan, Michael Shannon, Sofia Boutella, Laura Harrier, Lilly Singh', 'Ciencia ficción', 'Película', 26, 1),
(32, 'Los vergadores', '1969-01-01', 'Santiago Segura', 'Belen Esteban', 'Fantasía', 'Película', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `ID` int(11) NOT NULL,
  `ID_LIBRO` int(11) DEFAULT NULL,
  `ID_PELICULA` int(11) DEFAULT NULL,
  `CLIENTE_ID` int(11) NOT NULL,
  `FECHA_RESERVA` date NOT NULL,
  `DEVUELTO` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`ID`, `ID_LIBRO`, `ID_PELICULA`, `CLIENTE_ID`, `FECHA_RESERVA`, `DEVUELTO`) VALUES
(1, NULL, 1, 8, '2025-12-13', 1),
(2, 1, NULL, 6, '2025-12-13', 1),
(3, 4, NULL, 4, '2025-12-13', 1),
(4, 1, NULL, 4, '2025-12-13', 1),
(5, 1, NULL, 12, '2025-12-13', 1),
(6, 29, NULL, 10, '2025-12-13', 1),
(7, 7, NULL, 19, '2025-12-13', 1),
(8, NULL, 10, 7, '2025-12-13', 1),
(9, NULL, 11, 21, '2025-12-14', 1),
(10, NULL, 14, 1, '2025-12-14', 1),
(11, NULL, 1, 21, '2025-12-14', 1),
(12, NULL, 30, 14, '2025-12-14', 1),
(13, 30, NULL, 14, '2025-12-14', 1),
(14, NULL, 4, 20, '2025-12-14', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `Usuario` varchar(255) NOT NULL,
  `Contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Usuario`, `Contraseña`) VALUES
(4, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
(5, 'user', 'e606e38b0d8c19b24cf0ee3808183162ea7cd63ff7912dbb22b5e803286b4446');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_aurores` (`AUTOR_ID`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_adaptacion` (`ADAPTACION_ID`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_LIBRO` (`ID_LIBRO`),
  ADD KEY `ID_PELICULA` (`ID_PELICULA`),
  ADD KEY `CLIENTE_ID` (`CLIENTE_ID`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autores`
--
ALTER TABLE `autores`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `fk_aurores` FOREIGN KEY (`AUTOR_ID`) REFERENCES `autores` (`ID`);

--
-- Filtros para la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD CONSTRAINT `fk_adaptacion` FOREIGN KEY (`ADAPTACION_ID`) REFERENCES `libros` (`ID`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`ID_LIBRO`) REFERENCES `libros` (`ID`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`ID_PELICULA`) REFERENCES `peliculas` (`ID`),
  ADD CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`CLIENTE_ID`) REFERENCES `clientes` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
