-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-08-2016 a las 19:22:45
-- Versión del servidor: 5.6.25
-- Versión de PHP: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tutorial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cargo`
--

CREATE TABLE IF NOT EXISTS `tbl_cargo` (
  `cargo_id` int(11) NOT NULL,
  `cargo_nombre_cargo` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='	';

--
-- Volcado de datos para la tabla `tbl_cargo`
--

INSERT INTO `tbl_cargo` (`cargo_id`, `cargo_nombre_cargo`) VALUES
(1, 'Gerente'),
(2, 'Diseñador'),
(3, 'Asistente de Certificación'),
(4, 'Responsable de Sistemas'),
(5, 'Responsable de Certificación'),
(6, 'Responsable de Eventos'),
(7, 'Responsable de Capacitacion'),
(8, 'Responsable de Recursos Humanos'),
(9, 'Asistente Administrativo'),
(10, 'Asistente Contable'),
(11, 'Coordinador de Entrenamiento'),
(12, 'Mantenimiento'),
(13, 'Entrenador'),
(14, 'Coordinadora de Sistemas de Gestión'),
(15, 'Coordinador de Entrenamiento'),
(16, 'Asistente de Sistemas'),
(17, 'Auxiliar Contable'),
(18, 'Auxiliar Administrativo'),
(19, 'Administrador'),
(20, 'Asistente de Capacitacion'),
(21, 'Responsable de Seguridad, Higiene, Salud Ocupacional y Medio Ambiente'),
(22, 'Responsable de Oficina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_estado_civil`
--

CREATE TABLE IF NOT EXISTS `tbl_estado_civil` (
  `ecivil_id` int(11) NOT NULL,
  `ecivil_nombre_estado_civil` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_estado_civil`
--

INSERT INTO `tbl_estado_civil` (`ecivil_id`, `ecivil_nombre_estado_civil`) VALUES
(1, 'Soltero'),
(2, 'Casado'),
(3, 'Divorciado'),
(4, 'Conviviente'),
(5, 'Viudo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_informacion_personal`
--

CREATE TABLE IF NOT EXISTS `tbl_informacion_personal` (
  `pinfo_id` int(5) NOT NULL,
  `pais_id` int(11) NOT NULL,
  `tdoc_id` int(11) DEFAULT NULL,
  `ecivil_id` int(11) DEFAULT NULL,
  `usu_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  `pinfo_nro_documento` int(11) DEFAULT NULL,
  `pinfo_fecha_nacimiento` date DEFAULT NULL,
  `pinfo_nombre` varchar(100) NOT NULL,
  `pinfo_apellido` varchar(200) NOT NULL,
  `pinfo_sexo` char(1) NOT NULL,
  `pinfo_telefono` varchar(20) DEFAULT NULL,
  `pinfo_celular` varchar(20) DEFAULT NULL,
  `pinfo_correo_personal` varchar(200) DEFAULT NULL,
  `pinfo_direccion` varchar(200) DEFAULT NULL,
  `jefe_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_informacion_personal`
--

INSERT INTO `tbl_informacion_personal` (`pinfo_id`, `pais_id`, `tdoc_id`, `ecivil_id`, `usu_id`, `cargo_id`, `pinfo_nro_documento`, `pinfo_fecha_nacimiento`, `pinfo_nombre`, `pinfo_apellido`, `pinfo_sexo`, `pinfo_telefono`, `pinfo_celular`, `pinfo_correo_personal`, `pinfo_direccion`, `jefe_id`) VALUES
(14, 1, 2, 2, 265, 1, 9517085, '1969-12-04', 'Fernando Hidelbrando', 'Borja Anorga', 'M', '445-3994', '999-129-999', 'fborja@isem.org.pe', 'Av  Tejada 259 Dpto  603  Urb  San Antonio  Miraflores', 0),
(32, 1, 2, 1, 14, 16, 46926625, '1992-04-09', 'Marcos Esteban', 'Rodriguez Lopez', 'M', '3233578', '985492904', 'erodriguez@isem.org.pe', 'Calle Simon Noguera 450  Urb  Tupac Amaru  San Luis', 268),
(38, 1, 2, 1, 268, 4, 10140434, '1975-07-31', 'Ricardo', 'Rojas Del Aguila', 'M', '02222222', '996599075', 'ricardorojasdelaguila@hotmail.com', 'Av  General Garzon 942 # 4  Jesus Maria', 265);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_modulo`
--

CREATE TABLE IF NOT EXISTS `tbl_modulo` (
  `id_modulo` int(11) NOT NULL,
  `mod_descripcion` varchar(100) DEFAULT NULL,
  `mod_estado` int(11) DEFAULT NULL,
  `mod_orden` int(11) DEFAULT NULL,
  `mod_icono` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Modulos para Menu';

--
-- Volcado de datos para la tabla `tbl_modulo`
--

INSERT INTO `tbl_modulo` (`id_modulo`, `mod_descripcion`, `mod_estado`, `mod_orden`, `mod_icono`) VALUES
(1, 'Inicio', 1, 1, '<i class="fa fa-dashboard"></i>'),
(2, 'Administrador', 1, 2, '<i class="fa fa-child"></i>'),
(4, 'Configuración', 1, 4, '<i class="fa fa-cogs"></i>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_pagina`
--

CREATE TABLE IF NOT EXISTS `tbl_pagina` (
  `id_pagina` int(11) NOT NULL,
  `pag_padre` int(11) NOT NULL DEFAULT '0',
  `id_mod` int(11) DEFAULT NULL,
  `pag_descripcion` varchar(100) DEFAULT NULL,
  `pag_ruta` varchar(500) DEFAULT NULL,
  `pag_slug` text,
  `pag_estado` int(11) DEFAULT NULL,
  `pag_visible` int(11) DEFAULT NULL,
  `pag_orden` int(11) DEFAULT NULL,
  `pag_icono` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Tabla para paginas internas (dentro de cada modulo)';

--
-- Volcado de datos para la tabla `tbl_pagina`
--

INSERT INTO `tbl_pagina` (`id_pagina`, `pag_padre`, `id_mod`, `pag_descripcion`, `pag_ruta`, `pag_slug`, `pag_estado`, `pag_visible`, `pag_orden`, `pag_icono`) VALUES
(1, 0, 2, 'Lista ', 'administrador', 'administrador', 1, 1, 1, NULL),
(13, 0, 1, 'Dashboard', 'dashboard', 'dashboard', 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_pais`
--

CREATE TABLE IF NOT EXISTS `tbl_pais` (
  `pais_id` int(11) NOT NULL,
  `pais_nombre` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_pais`
--

INSERT INTO `tbl_pais` (`pais_id`, `pais_nombre`) VALUES
(1, 'Peru'),
(2, 'Colombia'),
(3, 'Chile'),
(4, 'Argentina'),
(5, 'Estados Unidos'),
(6, 'España'),
(7, 'Uruguay'),
(8, 'Paraguay'),
(9, 'Mexico'),
(10, 'Brasil'),
(11, 'Venezuela'),
(12, 'Ecuador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_permisos`
--

CREATE TABLE IF NOT EXISTS `tbl_permisos` (
  `id_permiso` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `id_pagina` int(11) DEFAULT NULL,
  `per_insert` int(11) DEFAULT NULL,
  `per_ver` int(11) DEFAULT NULL,
  `per_update` int(11) DEFAULT NULL,
  `per_delete` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COMMENT='Tabla de asignacion de permisos.';

--
-- Volcado de datos para la tabla `tbl_permisos`
--

INSERT INTO `tbl_permisos` (`id_permiso`, `id_rol`, `id_pagina`, `per_insert`, `per_ver`, `per_update`, `per_delete`) VALUES
(1, 2, 13, 1, 1, 1, 1),
(16, 1, 13, 1, 1, 1, 1),
(17, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_rol`
--

CREATE TABLE IF NOT EXISTS `tbl_rol` (
  `id_rol` int(11) NOT NULL,
  `rol_descripcion` varchar(80) DEFAULT NULL,
  `rol_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Creacion de roles para el perfil';

--
-- Volcado de datos para la tabla `tbl_rol`
--

INSERT INTO `tbl_rol` (`id_rol`, `rol_descripcion`, `rol_estado`) VALUES
(1, 'Administrador', 1),
(2, 'Usuario', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tipo_documento`
--

CREATE TABLE IF NOT EXISTS `tbl_tipo_documento` (
  `tdoc_id` int(11) NOT NULL,
  `tdoc_nombre_documento` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_tipo_documento`
--

INSERT INTO `tbl_tipo_documento` (`tdoc_id`, `tdoc_nombre_documento`) VALUES
(1, 'Libreta Electoral'),
(2, 'DNI'),
(3, 'Carnet Extrangeria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario`
--

CREATE TABLE IF NOT EXISTS `tbl_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `usu_email` varchar(200) DEFAULT NULL,
  `usu_clave` varchar(200) DEFAULT NULL,
  `usu_imagen` text,
  `usu_fecha_registro` datetime DEFAULT NULL,
  `usu_cambio_clave` int(11) DEFAULT NULL,
  `usu_estado` int(11) DEFAULT NULL,
  `usu_codigo` varchar(10) DEFAULT NULL,
  `salt` text NOT NULL,
  `last_login` datetime NOT NULL,
  `remember_code` text NOT NULL,
  `forgotten_password_code` text NOT NULL,
  `forgotten_password_time` text NOT NULL,
  `active` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=363 DEFAULT CHARSET=latin1 COMMENT='Tabla de usuarios del sistema';

--
-- Volcado de datos para la tabla `tbl_usuario`
--

INSERT INTO `tbl_usuario` (`id_usuario`, `id_rol`, `usu_email`, `usu_clave`, `usu_imagen`, `usu_fecha_registro`, `usu_cambio_clave`, `usu_estado`, `usu_codigo`, `salt`, `last_login`, `remember_code`, `forgotten_password_code`, `forgotten_password_time`, `active`) VALUES
(14, 1, 'erodriguez@isem.org.pe', 'f2f4c191338ab85640de8f2ec8296fb771132611', '50642000ea7cd_imprimirimagenesdepayasos1jpg.jpg', '2012-08-27 20:28:43', 1, 1, 'I-0057', '', '0000-00-00 00:00:00', '', '06399f771a946992591a14b5a53e589934cb55da', '1469647757', '1'),
(265, 2, 'zkated@gmail.com', '65ca0bce43c8c0da435144e02651cc308897ff40', '', '2012-09-17 20:55:32', 1, 1, 'I-0001', '', '0000-00-00 00:00:00', '', '', '', '1'),
(268, 1, 'rrojas@isem.org.pe', 'a631270a64675f27e4db0c323a9ab15eb7368322', '506d53145b50f_foto_ricardojpg.jpg', '2012-09-17 20:55:32', 1, 1, 'I-0004', '', '0000-00-00 00:00:00', '5GKFxX0BTOKVn2Dtejoen.', 'RZEy-4BnDGOgNKaqsQxNke4eef11e9b62ae8d97c', '1469562477', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temp_table`
--

CREATE TABLE IF NOT EXISTS `temp_table` (
  `idtable` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `temp_table`
--

INSERT INTO `temp_table` (`idtable`, `nombre`, `fecha`, `estado`) VALUES
(11, 'as', '2016-08-26', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_cargo`
--
ALTER TABLE `tbl_cargo`
  ADD PRIMARY KEY (`cargo_id`);

--
-- Indices de la tabla `tbl_estado_civil`
--
ALTER TABLE `tbl_estado_civil`
  ADD PRIMARY KEY (`ecivil_id`);

--
-- Indices de la tabla `tbl_informacion_personal`
--
ALTER TABLE `tbl_informacion_personal`
  ADD PRIMARY KEY (`pinfo_id`),
  ADD KEY `fk_tbl_informacion_personal_tbl_pais1_idx` (`pais_id`),
  ADD KEY `fk_tbl_informacion_personal_tbl_tipo_documento1_idx` (`tdoc_id`),
  ADD KEY `fk_tbl_informacion_personal_tbl_estado_civil1_idx` (`ecivil_id`),
  ADD KEY `fk_tbl_informacion_personal_tbl_usuarios1_idx` (`usu_id`),
  ADD KEY `fk_tbl_informacion_personal_tbl_cargo1_idx` (`cargo_id`);

--
-- Indices de la tabla `tbl_modulo`
--
ALTER TABLE `tbl_modulo`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `tbl_pagina`
--
ALTER TABLE `tbl_pagina`
  ADD PRIMARY KEY (`id_pagina`),
  ADD KEY `fk_mod_pag_idx` (`id_mod`);

--
-- Indices de la tabla `tbl_pais`
--
ALTER TABLE `tbl_pais`
  ADD PRIMARY KEY (`pais_id`);

--
-- Indices de la tabla `tbl_permisos`
--
ALTER TABLE `tbl_permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `fk_pagina_permiso_idx` (`id_pagina`),
  ADD KEY `fk_rol_permiso_idx` (`id_rol`);

--
-- Indices de la tabla `tbl_rol`
--
ALTER TABLE `tbl_rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tbl_tipo_documento`
--
ALTER TABLE `tbl_tipo_documento`
  ADD PRIMARY KEY (`tdoc_id`);

--
-- Indices de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_rol_usuario_idx` (`id_rol`);

--
-- Indices de la tabla `temp_table`
--
ALTER TABLE `temp_table`
  ADD PRIMARY KEY (`idtable`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_cargo`
--
ALTER TABLE `tbl_cargo`
  MODIFY `cargo_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `tbl_estado_civil`
--
ALTER TABLE `tbl_estado_civil`
  MODIFY `ecivil_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tbl_informacion_personal`
--
ALTER TABLE `tbl_informacion_personal`
  MODIFY `pinfo_id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT de la tabla `tbl_modulo`
--
ALTER TABLE `tbl_modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `tbl_pagina`
--
ALTER TABLE `tbl_pagina`
  MODIFY `id_pagina` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `tbl_pais`
--
ALTER TABLE `tbl_pais`
  MODIFY `pais_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `tbl_permisos`
--
ALTER TABLE `tbl_permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `tbl_rol`
--
ALTER TABLE `tbl_rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tbl_tipo_documento`
--
ALTER TABLE `tbl_tipo_documento`
  MODIFY `tdoc_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=363;
--
-- AUTO_INCREMENT de la tabla `temp_table`
--
ALTER TABLE `temp_table`
  MODIFY `idtable` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_informacion_personal`
--
ALTER TABLE `tbl_informacion_personal`
  ADD CONSTRAINT `fk_tbl_informacion_personal_tbl_cargo1` FOREIGN KEY (`cargo_id`) REFERENCES `tbl_cargo` (`cargo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_informacion_personal_tbl_estado_civil1` FOREIGN KEY (`ecivil_id`) REFERENCES `tbl_estado_civil` (`ecivil_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_informacion_personal_tbl_pais1` FOREIGN KEY (`pais_id`) REFERENCES `tbl_pais` (`pais_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_informacion_personal_tbl_tipo_documento1` FOREIGN KEY (`tdoc_id`) REFERENCES `tbl_tipo_documento` (`tdoc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_informacion_personal_tbl_usuarios1` FOREIGN KEY (`usu_id`) REFERENCES `tbl_usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_pagina`
--
ALTER TABLE `tbl_pagina`
  ADD CONSTRAINT `fk_mod_pag` FOREIGN KEY (`id_mod`) REFERENCES `tbl_modulo` (`id_modulo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_permisos`
--
ALTER TABLE `tbl_permisos`
  ADD CONSTRAINT `fk_pagina_permiso` FOREIGN KEY (`id_pagina`) REFERENCES `tbl_pagina` (`id_pagina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rol_permiso` FOREIGN KEY (`id_rol`) REFERENCES `tbl_rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD CONSTRAINT `fk_rol_usuario` FOREIGN KEY (`id_rol`) REFERENCES `tbl_rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
