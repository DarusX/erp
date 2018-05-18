SET @anio = 2017;

SELECT
	vd.id_producto ,
	p.codigo_producto ,
	p.descripcion ,
	pf.familia ,
	pl.linea ,
	p.factor_conversion ,
	(SELECT
	ROUND(SUM(dias) / COUNT(dias), 1)
FROM
	(
		SELECT
			ROUND(
				(
					DATEDIFF(aeo.fecha_entrada,co.fecha_autorizacion)
				)
			) AS dias,
			cod.id_producto
		FROM
			compras_ordenes_descripcion AS cod
		LEFT JOIN compras_ordenes AS co ON co.id_orden = cod.id_orden
		LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeo.id_orden = co.id_orden
		WHERE aeo.id_orden IS NOT NULL
		AND YEAR(co.fecha_autorizacion) = @anio
		ORDER BY
			co.id_orden DESC
	) AS dias WHERE dias.id_producto = vd.id_producto) AS 'dias entrega promedio',
	SUM(vd.cantidad) AS cantidad ,
	ROUND(SUM(vd.cantidad * vd.precio) , 2) AS 'monto venta' ,
	ROUND(SUM(vd.precio) , 2) AS precio ,
	ROUND(SUM(vd.costo * vd.cantidad) , 2) AS 'monto costo' ,
	ROUND(SUM(vd.costo) , 2) AS costo ,
	ROUND(
		(
			SELECT
				ROUND(SUM(vd3.cantidad * vd3.precio) , 2)
			FROM
				ventas_descripcion AS vd3
			WHERE
				vd3.id_venta = v.id_venta
			AND YEAR(vd3.fecha) = @anio
		) /(
			SELECT COUNT(*) FROM ventas_descripcion AS vd4 WHERE vd4.id_venta = v.id_venta AND YEAR(vd4.fecha) = @anio
		) ,
		2
	) AS '$ prom ticket' ,
	(
		SELECT COUNT(*) FROM ventas_descripcion AS vd2 WHERE vd2.id_venta = v.id_venta AND YEAR(vd2.fecha) = @anio
	) AS '# sku\s' ,
	(
		SELECT ROUND(SUM(vd10.cantidad) / COUNT(*) , 2) FROM ventas_descripcion AS vd10 WHERE vd10.id_producto = vd.id_producto AND YEAR(vd10.fecha) = @anio
	) AS 'promedio' ,
	IFNULL(
		(
			SELECT vd5.cantidad FROM ventas_descripcion AS vd5 WHERE vd5.id_producto = vd.id_producto AND YEAR(vd5.fecha) = @anio GROUP BY vd5.cantidad HAVING COUNT(*) > 1 ORDER BY COUNT(*) DESC LIMIT 1
		) ,
		0
	) AS 'moda' ,
	IFNULL(
		(
			SELECT COUNT(*) FROM ventas_descripcion AS vd6 WHERE vd6.id_producto = vd.id_producto AND YEAR(vd6.fecha) = @anio GROUP BY vd6.cantidad HAVING COUNT(*) > 1 ORDER BY COUNT(*) DESC LIMIT 1
		) ,
		0
	) AS '# moda' ,
	(
		SELECT MIN(vd7.cantidad) FROM ventas_descripcion AS vd7 WHERE vd7.id_producto = vd.id_producto AND YEAR(vd7.fecha) = @anio LIMIT 1
	) AS 'min' ,
	(
		SELECT MAX(vd8.cantidad) FROM ventas_descripcion AS vd8 WHERE vd8.id_producto = vd.id_producto AND YEAR(vd8.fecha) = @anio LIMIT 1
	) AS 'max' ,
	(
		SELECT COUNT(*) FROM ventas_descripcion AS vd9 WHERE vd9.id_producto = vd.id_producto AND YEAR(vd9.fecha) = @anio
	) AS '# tickets totales' FROM ventas_descripcion AS vd LEFT JOIN ventas AS v ON v.id_venta = vd.id_venta LEFT JOIN productos AS p ON p.id_producto = vd.id_producto LEFT JOIN productos_familias AS pf ON pf.id_familia = p.id_familia LEFT JOIN productos_lineas AS pl ON pl.id_linea = p.id_linea WHERE YEAR(vd.fecha) = @anio GROUP BY vd.id_producto;