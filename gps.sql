-- id_geocerca | id_unidad | cuando_alarma |    hi    |    hf    | dom | lun | mar | mie | jue | vie | sab | alarma 
-- -------------+-----------+---------------+----------+----------+-----+-----+-----+-----+-----+-----+-----+--------
--            3 |      1991 |             2 | 11:00:00 | 04:00:00 | t   | t   | t   | t   | t   | t   | t   | t
--            5 |      1991 |             2 | 11:00:00 | 04:00:00 | t   | t   | t   | t   | t   | t   | t   | t
--            6 |      1991 |             2 | 11:00:00 | 04:00:00 | t   | t   | t   | t   | t   | t   | t   | t
--            7 |      1991 |             2 | 11:00:00 | 04:00:00 | t   | t   | t   | t   | t   | t   | t   | t

-- importante declaran las variables luego de un select 
DECLARE
	new_id_geocerca INTEGER;

	dia INTEGER DEFAULT 0;

	contador_geo INTEGER DEFAULT 0;

	gid_geocerca INTEGER;

	gtipo_geocerca INTEGER;

	gid_unidad INTEGER;

	gcuando_alarma INTEGER;
	
	galarma INTEGER;

	hora_actual TIME WITHOUT TIME ZONE;
	
	ghi TIME WITHOUT TIME ZONE;
	
	ghf TIME WITHOUT TIME ZONE;
	
	hora_actual TIME WITHOUT TIME ZONE;

	gdom BOOLEAN DEFAULT FALSE;

	glun BOOLEAN DEFAULT FALSE;

	gmar BOOLEAN DEFAULT FALSE;

	gmie BOOLEAN DEFAULT FALSE;

	gjue BOOLEAN DEFAULT FALSE;

	gvie BOOLEAN DEFAULT FALSE;

	gsab BOOLEAN DEFAULT FALSE;
	-- alertar BOOLEAN DEFAULT FALSE;
BEGIN
	FOR new_id_geocerca IN SELECT id_geocerca FROM geounid WHERE id_unidad = NEW.id_unidad LOOP
		
		SELECT id_geocerca , id_unidad , cuando_alarma , hi , hf , dom , lun , mar , mie , jue , vie , sab , alarma,(NEW.fecha_hora_gps)::TIME WITHOUT TIME ZONE 
		INTO gid_geocerca, gid_unidad, gcuando_alarma, ghi ,ghf ,gdom ,glun ,gmar ,gmie ,gjue ,gvie ,gsab ,galarma,hora_actual 
		FROM geounid WHERE id_unidad = NEW.id_unidad and id_geocerca = new_id_geocerca;
		
		dia := (SELECT EXTRACT(DOW FROM NEW.fecha_hora_gps));
		/* esta dentro del dia*/
		IF ( (dia = 0 AND dom = TRUE) OR (dia = 1 AND lun = TRUE) OR (dia = 2 AND mar = TRUE) OR 

	     (dia = 3 AND mie = TRUE) OR (dia = 4 AND jue = TRUE) OR (dia = 5 AND vie = TRUE) OR 

	     (dia = 6 AND sab = TRUE) ) THEN  

		 	/* esta dentro de la hora*/

				SELECT tipo_geocerca INTO gtipo_geocerca FROM geocercas WHERE id_geocerca = new_id_geocerca;

				-- si es circulo
				IF ( gtipo_geocerca == 1) THEN 
				-- si es poligono
				ELSIF( gtipo_geocerca == 2) THEN 
					-- cuando salga de la geocerca
 					IF ( gcuando_alarma == 1) THEN 
						
						IF ( hora_actual >= hi AND hora_actual <= hf ) THEN 

							SELECT count(id_geocerca) INTO contador_geo FROM geocercas 
	 						WHERE ST_Contains(polygon, ST_GeomFromText(NEW.geo_posicion)) 
	 						and id_geocerca = new_id_geocerca;

	 						IF (contador_geo < 1) THEN
	 							-- Generar alarma o notificacion segun sea el caso
	 						ELSE 
								RAISE EXCEPTION 'El vehiculo esta dentro de la geocerca';
	 						END IF;

						ELSE 
							RAISE EXCEPTION 'No esta dentro de la hora limite de la geocerca';
			            END IF;
					-- cuando entre a la geocerca
					ELSIF(gcuando_alarma == 2) THEN 
						IF ( hora_actual >= hi AND hora_actual <= hf ) THEN

							SELECT count(id_geocerca) INTO contador_geo FROM geocercas 
	 						WHERE ST_Contains(polygon, ST_GeomFromText(NEW.geo_posicion)) 
	 						and id_geocerca = new_id_geocerca;

	 						IF (contador_geo >= 1) THEN
	 							-- Generar alarma o notificacion segun sea el caso
	 						ELSE 
								RAISE EXCEPTION 'El vehiculo esta fuera de la geocerca';
	 						END IF;

						ELSE 
							RAISE EXCEPTION 'No esta dentro de la hora limite de la geocerca';
			            END IF;
					-- cuando salga de la geocerca y fuera del horario
					ELSIF( cuando_alarma == 3) THEN

						    SELECT count(id_geocerca) INTO contador_geo FROM geocercas 
	 						WHERE ST_Contains(polygon, ST_GeomFromText(NEW.geo_posicion)) 
	 						and id_geocerca = new_id_geocerca;

	 						IF (contador_geo < 1) THEN
	 							-- Generar alarma o notificacion segun sea el caso
	 						ELSE 
								RAISE EXCEPTION 'El vehiculo esta dentro de la geocerca';
	 						END IF;
 					
 					END IF;

				END IF;


		ELSE

			RAISE EXCEPTION 'No esta dentro del dia limite de la geocerca';

		END IF;
	
	END LOOP;
	RETURN NEW;
END