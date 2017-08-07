delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `controlAnioClientes`()
BEGIN
	SELECT DISTINCT YEAR(Fecha_Alta) FROM clientes ORDER BY YEAR(Fecha_Alta) DESC;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `controlAnioEmpleados`()
BEGIN
	SELECT DISTINCT YEAR(Fecha_Alta) FROM empleados ORDER BY YEAR(Fecha_Alta) DESC;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `controlDepartamentos`()
BEGIN
SELECT DISTINCT Nombre AS Departamentos
	FROM departamentos
ORDER BY departamentos.Id_Departamento ASC;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `controlEmpleados`(IN dpto VARCHAR(50))
BEGIN
SELECT DISTINCT concat(Nombre,' (', Cargo,')') AS Empleados
	FROM empleados
WHERE empleados.DEPARTAMENTOS_Nombre = dpto
ORDER BY empleados.Id_Empleado ASC;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `controlProyectos`()
BEGIN
SELECT DISTINCT Nombre AS Proyectos
	FROM proyectos
ORDER BY proyectos.Id_Proyecto ASC;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `controlTipoMaterial`()
BEGIN
SELECT DISTINCT Tipo FROM material;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `datosEmpleado`(IN empl VARCHAR(50))
BEGIN
SELECT  Nombre, Ciudad, Pais, 
		Fecha_Alta, DEPARTAMENTOS_Nombre AS Departamento, Cargo, Sueldo
FROM empleados
where empl LIKE CONCAT('%', empleados.Nombre ,'%');
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `datosMaterial`(IN empl VARCHAR(50))
BEGIN
SELECT  Nombre, DEPARTAMENTOS_Nombre AS Departamento, Cargo, 
		m.Tipo, m.Marca, m.Coste, m.Estado
FROM empleados
LEFT JOIN material m
ON(empleados.Id_Empleado = m.Id_Empleado)
WHERE empl LIKE CONCAT('%', empleados.Nombre ,'%');
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `datosMaterialRef`(IN numserie VARCHAR(50))
BEGIN
SELECT Tipo, Marca, Coste, DATE_FORMAT(Fecha_Compra,'%d/%m/%Y') AS Fecha_Compra,
	   Estado, empleados.Nombre AS Usuario,
	   empleados.DEPARTAMENTOS_Nombre AS Departamento	
FROM material 
LEFT JOIN empleados
ON (empleados.Id_Empleado = material.Id_Empleado)
WHERE Ref = numserie;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `datosProyecto`(IN proy VARCHAR(50))
BEGIN
SELECT proyectos.Nombre,
	   proyectos.Descripcion,
	   DATE_FORMAT(proyectos.Fecha_Inicio,'%d/%m/%Y') AS Fecha_Inicio,
	   IFNULL(proyectos.Fecha_Fin,0) AS Fecha_Fin,
	   (COUNT(clientes.Id_Cliente) / PERIOD_DIFF(
			DATE_FORMAT(IFNULL((proyectos.Fecha_Fin), CURRENT_DATE), '%Y%m'), 
			DATE_FORMAT(proyectos.Fecha_Inicio, '%Y%m'))) AS Clientes_Mes
FROM proyectos
LEFT JOIN clientes
ON (clientes.Proyecto = proyectos.nombre)
WHERE proy = proyectos.Nombre
GROUP BY proyectos.Id_Proyecto;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaArticulosProyecto`(IN proy VARCHAR(100))
BEGIN
SELECT 	Articulo,
		COUNT(Articulo) AS Ventas,
		IFNULL(SUM(clientes.Precio),0) + IFNULL(SUM(clientes.Cuota_mensual*
			PERIOD_DIFF(
			DATE_FORMAT(IFNULL((clientes.Fecha_Baja), CURRENT_DATE), '%Y%m'), 
			DATE_FORMAT(Fecha_Alta, '%Y%m'))),0) AS Recaudacion
FROM clientes
WHERE Proyecto = proy
GROUP BY clientes.Articulo
ORDER BY Ventas desc
LIMIT 5;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaCliProyectos`(IN estado VARCHAR(10))
BEGIN
IF(estado = "activos") THEN 
	SELECT proyectos.Nombre AS Proyectos, COUNT(clientes.Id_Cliente) AS Clientes
FROM proyectos
LEFT JOIN clientes
ON (clientes.Proyecto = proyectos.Nombre)
WHERE (Fecha_Fin IS NULL)
GROUP BY proyectos.Id_Proyecto;
END IF;
IF(estado = "inactivos") THEN 
	SELECT proyectos.Nombre AS Proyectos, COUNT(clientes.Id_Cliente) AS Clientes
FROM proyectos
LEFT JOIN clientes
ON (clientes.Proyecto = proyectos.Nombre)
WHERE ((Fecha_Fin IS NOT NULL)AND(Fecha_Fin < CURRENT_DATE))
GROUP BY proyectos.Id_Proyecto;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaEmpleados`(IN Fecha INT)
BEGIN
IF Fecha IS NOT NULL THEN
	SELECT aux_mes.Mes, COUNT(empleados.Id_Empleado) AS Empleados FROM aux_mes
	LEFT JOIN empleados
	ON ((YEAR(empleados.Fecha_Alta)<Fecha) 
	OR ((YEAR(empleados.Fecha_Alta)=Fecha) AND (aux_mes.id_Mes>=MONTH(empleados.Fecha_Alta))))
	AND((empleados.Fecha_Baja IS NULL)
	OR (YEAR(empleados.Fecha_Baja)>Fecha)
	OR((YEAR(empleados.Fecha_Baja)=Fecha) AND (aux_mes.id_Mes<MONTH(empleados.Fecha_Baja))))
	WHERE aux_mes.id_Mes <= MONTH(CURRENT_DATE)
	GROUP BY aux_mes.id_Mes;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaGanancias`()
BEGIN
SELECT 
 Aux_mes.Mes,
 IFNULL(SUM(clientes.Precio),0) + (
SELECT IFNULL(SUM(clientes.cuota_mensual),0)
FROM
 clientes
WHERE
 ((YEAR(clientes.Fecha_Alta) = YEAR(CURRENT_DATE))
 AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_mes)
 OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_mes)
 AND((clientes.Fecha_Baja IS NULL)
 OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_mes)))))
) as Ganancias
FROM
 aux_mes
LEFT JOIN clientes
ON
	((YEAR(clientes.Fecha_Alta) = YEAR(CURRENT_DATE))
	AND (MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes))
WHERE (aux_mes.id_mes) <= MONTH(CURRENT_DATE)
GROUP BY Aux_mes.id_Mes;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaGananciasAnio`(IN Anio INT)
BEGIN
IF(Anio = YEAR(CURRENT_DATE))THEN
SELECT 
 Aux_mes.Mes,
 IFNULL(SUM(clientes.Precio),0) + (
SELECT IFNULL(SUM(clientes.cuota_mensual),0)
FROM
 clientes
WHERE
 ((YEAR(clientes.Fecha_Alta) = YEAR(CURRENT_DATE))
 AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_mes)
 OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_mes)
 AND((clientes.Fecha_Baja IS NULL)
 OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_mes)))))
) as Ganancias
FROM
 aux_mes
LEFT JOIN clientes
ON
	((YEAR(clientes.Fecha_Alta) = Anio)
	AND (MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes))
WHERE (aux_mes.id_mes) <= MONTH(CURRENT_DATE)
GROUP BY Aux_mes.id_Mes;
ELSE
SELECT 
 Aux_mes.Mes,
 IFNULL(SUM(clientes.Precio),0) + (
SELECT IFNULL(SUM(clientes.cuota_mensual),0)
FROM
 clientes
WHERE
 ((YEAR(clientes.Fecha_Alta) = YEAR(CURRENT_DATE))
 AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_mes)
 OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_mes)
 AND((clientes.Fecha_Baja IS NULL)
 OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_mes)))))
) as Ganancias
FROM
 aux_mes
LEFT JOIN clientes
ON
	((YEAR(clientes.Fecha_Alta) = Anio)
	AND (MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes))
GROUP BY Aux_mes.id_Mes;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaGanProyectos`(IN estado VARCHAR(10))
BEGIN
IF (estado = "activos") THEN
SELECT proyectos.Nombre AS Proyectos,
IFNULL(SUM(clientes.Precio),0) + IFNULL(SUM(clientes.Cuota_mensual*
PERIOD_DIFF(
	DATE_FORMAT(IFNULL((clientes.Fecha_Baja), CURRENT_DATE), '%Y%m'), 
	DATE_FORMAT(Fecha_Alta, '%Y%m'))),0) AS Ganancias
FROM proyectos
LEFT JOIN clientes
ON (clientes.Proyecto = proyectos.Nombre)
WHERE (proyectos.Fecha_Fin IS NULL)
GROUP BY proyectos.Id_Proyecto;
END IF;
IF (estado = "inactivos") THEN
SELECT proyectos.Nombre AS Proyectos,
IFNULL(SUM(clientes.Precio),0) + IFNULL(SUM(clientes.Cuota_mensual*
period_diff(
	date_format(IFNULL((clientes.Fecha_Baja), current_date), '%Y%m'), 
	date_format(Fecha_Alta, '%Y%m'))),0) AS Ganancias
FROM proyectos
LEFT JOIN clientes
ON (clientes.Proyecto = proyectos.Nombre)
WHERE (proyectos.Fecha_Fin IS NOT NULL)AND(Fecha_Fin < CURRENT_DATE)
GROUP BY proyectos.Id_Proyecto;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaIntegrantes`()
BEGIN
SELECT departamentos.Nombre AS Departamentos,
		COUNT(empleados.Id_Empleado) AS Integrantes
FROM departamentos
LEFT JOIN empleados
ON ((empleados.DEPARTAMENTOS_Id_Departamento = departamentos.Id_Departamento) AND
	(empleados.Fecha_Baja IS NULL))
GROUP BY departamentos.Id_Departamento;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaMantenimiento`(IN numserie VARCHAR(50))
BEGIN
SELECT IFNULL(DATE_FORMAT(mantenimiento.Fecha,'%d/%m/%y'),"") AS Fecha,
	   IFNULL(mantenimiento.Coste,0) AS Coste
FROM material
LEFT JOIN mantenimiento
ON (material.Id_Material = mantenimiento.MATERIAL_Id_Material)
WHERE material.Ref = NUMSERIE
ORDER BY mantenimiento.Fecha ASC;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaSexos`(IN Anio INT)
BEGIN
IF Anio IS NOT NULL THEN
SELECT 
 Sexo, IFNULL(COUNT(empleados.Sexo), 0) AS Cantidad
FROM
 empleados
WHERE
 ((YEAR(empleados.Fecha_Alta) <= Anio)
 AND ((empleados.Fecha_Baja IS NULL)
 OR (YEAR(empleados.Fecha_Baja) > Anio)))
GROUP BY Sexo;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaSueldos`()
BEGIN
SELECT departamentos.Nombre AS Departamentos,
		IFNULL(SUM(empleados.Sueldo),0) AS Sueldos
FROM departamentos
LEFT JOIN empleados
ON ((empleados.DEPARTAMENTOS_Id_Departamento = departamentos.Id_Departamento) AND
	(empleados.Fecha_Baja IS NULL))
GROUP BY departamentos.Id_Departamento;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaVentas`()
BEGIN
SELECT
	aux_mes.Mes,
	COUNT(clientes.Id_Cliente) AS Ventas
FROM
	aux_mes
		LEFT JOIN clientes 
			ON ((YEAR(clientes.Fecha_Alta) = YEAR(CURRENT_DATE)) 
			AND (MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes))
WHERE (aux_mes.id_Mes <= MONTH(CURRENT_DATE))
GROUP BY aux_mes.id_Mes;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `graficaVentasAnio`(IN Anio INT)
BEGIN
IF(Anio = YEAR(CURRENT_DATE))THEN
SELECT
	aux_mes.Mes,
	COUNT(clientes.Id_Cliente) AS Ventas
FROM
	aux_mes
		LEFT JOIN clientes 
			ON ((YEAR(clientes.Fecha_Alta) = Anio) 
			AND (MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes))
WHERE (aux_mes.id_Mes <= MONTH(CURRENT_DATE))
GROUP BY aux_mes.id_Mes;
ELSE
SELECT
	aux_mes.Mes,
	COUNT(clientes.Id_Cliente) AS Ventas
FROM
	aux_mes
		LEFT JOIN clientes 
			ON ((YEAR(clientes.Fecha_Alta) = Anio) 
			AND (MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes))
GROUP BY aux_mes.id_Mes;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `liquidezEmpresa`(IN Anio INT)
BEGIN
IF Anio IS NOT NULL THEN
SELECT
	(SELECT IFNULL(SUM(empleados.Sueldo),0)
	FROM 
	aux_mes,empleados
	WHERE
	(YEAR(empleados.Fecha_Alta) <= Anio)
	AND((MONTH(empleados.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(empleados.Fecha_Alta) < aux_mes.id_mes)
	AND((empleados.Fecha_Baja IS NULL)
	OR(YEAR(empleados.Fecha_Baja) > aux_mes.id_Mes)))) ) as Pagos,
	((SELECT IFNULL(SUM(clientes.Precio),0)
	FROM
	clientes
	WHERE
	(YEAR(clientes.Fecha_Alta) = Anio) ) + 
	(SELECT IFNULL(SUM(clientes.cuota_mensual),0)
	FROM
	aux_mes,clientes
	WHERE
	((YEAR(clientes.Fecha_Alta) <= Anio)
	AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_Mes)
	AND((clientes.Fecha_Baja IS NULL)
	OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_Mes))))) )) as Ganancias,
	(SELECT IFNULL(SUM(empleados.Sueldo),0)
	FROM 
	aux_mes,empleados
	WHERE
	(YEAR(empleados.Fecha_Alta) <= Anio)
	AND((MONTH(empleados.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(empleados.Fecha_Alta) < aux_mes.id_mes)
	AND((empleados.Fecha_Baja IS NULL)
	OR(YEAR(empleados.Fecha_Baja) > aux_mes.id_Mes)))) ) / 
	((SELECT IFNULL(SUM(clientes.Precio),0)
	FROM
	clientes
	WHERE
	(YEAR(clientes.Fecha_Alta) = Anio) ) + 
	(SELECT IFNULL(SUM(clientes.cuota_mensual),0)
	FROM
	aux_mes,clientes
	WHERE
	((YEAR(clientes.Fecha_Alta) <= Anio)
	AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_Mes)
	AND((clientes.Fecha_Baja IS NULL)
	OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_Mes))))) )) as Liquidez;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ratingEmpresa`(IN Anio INT)
BEGIN
IF Anio IS NOT NULL THEN
SELECT
	((SELECT IFNULL(SUM(empleados.Sueldo),0)
	FROM 
	aux_mes,empleados
	WHERE
	(YEAR(empleados.Fecha_Alta) <= Anio)
	AND((MONTH(empleados.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(empleados.Fecha_Alta) < aux_mes.id_mes)
	AND((empleados.Fecha_Baja IS NULL)
	OR(YEAR(empleados.Fecha_Baja) > aux_mes.id_Mes)))) ) + 
	(SELECT IFNULL(SUM(mantenimiento.Coste),0)
	FROM mantenimiento
	WHERE
	(YEAR(mantenimiento.Fecha) = Anio) )+ 
	(SELECT IFNULL(SUM(material.Coste),0)
	FROM material
	WHERE
	(YEAR(material.Fecha_Compra) = Anio) )) as Perdidas,
	((SELECT IFNULL(SUM(clientes.Precio),0)
	FROM
	clientes
	WHERE
	(YEAR(clientes.Fecha_Alta) = Anio) ) + 
	(SELECT IFNULL(SUM(clientes.cuota_mensual),0)
	FROM
	aux_mes,clientes
	WHERE
	((YEAR(clientes.Fecha_Alta) <= Anio)
	AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_Mes)
	AND((clientes.Fecha_Baja IS NULL)
	OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_Mes))))) )) as Ganancias,
	(((SELECT IFNULL(SUM(empleados.Sueldo),0)
	FROM 
	aux_mes,empleados
	WHERE
	(YEAR(empleados.Fecha_Alta) <= Anio)
	AND((MONTH(empleados.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(empleados.Fecha_Alta) < aux_mes.id_mes)
	AND((empleados.Fecha_Baja IS NULL)
	OR(YEAR(empleados.Fecha_Baja) > aux_mes.id_Mes)))) ) + 
	(SELECT IFNULL(SUM(mantenimiento.Coste),0)
	FROM mantenimiento
	WHERE
	(YEAR(mantenimiento.Fecha) = Anio) )+ 
	(SELECT IFNULL(SUM(mantenimiento.Coste),0)
	FROM mantenimiento
	WHERE
	(YEAR(mantenimiento.Fecha) = Anio) )+ 
	(SELECT IFNULL(SUM(material.Coste),0)
	FROM material
	WHERE
	(YEAR(material.Fecha_Compra) = Anio) )) / 
	((SELECT IFNULL(SUM(clientes.Precio),0)
	FROM
	clientes
	WHERE
	(YEAR(clientes.Fecha_Alta) = Anio) ) + 
	(SELECT IFNULL(SUM(clientes.cuota_mensual),0)
	FROM
	aux_mes,clientes
	WHERE
	((YEAR(clientes.Fecha_Alta) <= Anio)
	AND((MONTH(clientes.Fecha_Alta) = aux_mes.id_Mes)
	OR((MONTH(clientes.Fecha_Alta) < aux_mes.id_Mes)
	AND((clientes.Fecha_Baja IS NULL)
	OR(MONTH(clientes.Fecha_Baja) > aux_mes.id_Mes))))) ))) as Rating;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tablaCorporativa`()
BEGIN
SELECT
 departamentos.Nombre AS Departamento,
 Descripcion,
	COUNT(empleados.Id_Empleado) AS Trabajadores,
 EMPLEADO_JEFE_Nombre AS Director,
 director.Cargo,
	YEAR(CURRENT_DATE) - YEAR(director.Fecha_Alta) AS Antiguedad
FROM
 departamentos
		LEFT JOIN empleados ON departamentos.Id_Departamento = empleados.DEPARTAMENTOS_Id_Departamento
 LEFT JOIN empleados director ON departamentos.EMPLEADO_JEFE_DNI = director.DNI
GROUP BY departamentos.id_Departamento;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tablaEmpleados`(IN dpto VARCHAR(50))
BEGIN
IF dpto IS NOT NULL THEN
	SELECT Nombre, DNI, Pais,(YEAR(CURRENT_DATE) - YEAR(Fecha_Nac)) AS Edad,
	Cargo, Sueldo, GROUP_CONCAT(material.Tipo SEPARATOR ', ') AS Materiales
FROM empleados
LEFT JOIN material
ON (empleados.Id_Empleado = material.Id_Empleado)
WHERE (empleados.DEPARTAMENTOS_Nombre = dpto)
GROUP BY empleados.Id_Empleado;
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tablaMaterial`()
BEGIN
SELECT DISTINCT empleados.DEPARTAMENTOS_Nombre AS Departamento,
				IFNULL(SUM(material.Coste),0) AS Material
FROM empleados
LEFT JOIN material
ON (material.Id_Empleado = empleados.Id_Empleado)
GROUP BY empleados.DEPARTAMENTOS_Id_Departamento;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tablaProyectos`(IN estado VARCHAR(10))
BEGIN
IF estado = "activos" THEN
	SELECT Nombre, Descripcion, CONCAT('Activo desde ' , DATE_FORMAT(Fecha_Inicio,'%d/%m/%Y')) AS Estado
FROM proyectos
WHERE (Fecha_Fin IS NULL)OR(Fecha_Fin > CURRENT_DATE);
END IF;
IF estado = "inactivos" THEN
	SELECT Nombre, Descripcion, CONCAT('Inactivo desde ' , DATE_FORMAT(Fecha_Fin,'%d/%m/%Y')) AS Estado
FROM proyectos
WHERE ((Fecha_Fin IS NOT NULL)AND(Fecha_Fin < CURRENT_DATE));
END IF;
END$$


delimiter $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tablaTipoMaterial`(IN Tipo VARCHAR(30))
BEGIN
SELECT empleados.Nombre AS Usuario,
	   empleados.DEPARTAMENTOS_Nombre AS Departamento,
	   Tipo, Marca, Coste, Estado, Fecha_Compra FROM material
LEFT JOIN empleados
ON (empleados.Id_Empleado = material.Id_Empleado)
WHERE material.Tipo = Tipo
ORDER BY DEPARTAMENTOS_Nombre;
END$$