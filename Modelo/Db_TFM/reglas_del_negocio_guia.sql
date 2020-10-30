
/*******************************************************************************/
                        /*     REGLAS DEL NEGOCIO       */

/*******************************************************************************/

/*REGLA GENERAL DEL NEGOCIO (1):
    Se tendrán que registrar todos los movimientos de un usuario
    dentro del sistema a fin de garantizar el  no repudio de alguna acción,
    es decir, que niegue el haber realizado alguna operación dentro de este
*/


/*Regla 2:
    Será posible la inserción, actualización y baja de usuarios
    dentro del sistema, mas no su borrado (este se simulará con un estado diferente al
    activo e inactivo con el propósito de salvaguardar los datos y dar cumplimiento
    al requerimiento)
*/
/*******************************************************************************/
                        /*     CRUD USUARIO_SISTEMA      */
/*******************************************************************************/


/*******************************************************************************/
                        /*    VISTAS CRUD USUARIO      */
/*******************************************************************************/
DROP VIEW IF EXISTS VISTA_CRUD_USUARIO;
CREATE VIEW VISTA_CRUD_USUARIO
(NIP,NOMBRES,APELLIDOS,CORREO_INSTITUCIONAL,FECHA_ULTIMO_ACCESO,ESTADO_USUARIOS,ROL,ACCESO)
AS 
(SELECT US.NIP,US.NOMBRES,US.APELLIDOS,US.CORREO_INSTITUCIONAL,DATE_FORMAT(US.FECHA_ULTIMO_ACCESO, "%d-%m-%Y %H:%i:%s"),
EU.DESCRIPCION AS ESTADO_USUARIOS,RU.DESCRIPCION AS ROL,US.ACCESO FROM usuario_sistema US 
INNER JOIN rol_usuariO RU ON RU.ID_ROL_USUARIO=US.ID_ROL_USUARIO
INNER JOIN estado_usuario EU ON EU.ID_ESTADO_USUARIO=US.ID_ESTADO_USUARIO AND EU.DESCRIPCION<>'BAJA')

select * from VISTA_CRUD_USUARIO;


/*******************************************************************************/
                        /*PROCEDIMIENTOS ALMACENADOS CRUD USUARIO  */
/*******************************************************************************/
/*Procedimiento almacenado para la creación de Un usuario del sistema,*/
USE PRUEBA_BODEGA_TFM;
DROP PROCEDURE IF EXISTS PA_CREAR_USUARIO_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_CREAR_USUARIO_SISTEMA`(IN NIP_NUEVO INT, IN NOMBRES_USR_NUEVO VARCHAR(100),IN APELLIDOS_USR_NUEVO VARCHAR(100),IN CORREO_INSTITUCIONAL_USR_NUEVO VARCHAR(100),IN NOMBRE_ROL_USUARIO_NUEVO VARCHAR(100),IN CONTRASENIA_USR_NUEVO VARCHAR(100),IN NIP_ULT_USR_MOD INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20003' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de creación de usuario";
    END;
    START TRANSACTION;
        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR(NIP_ULT_USR_MOD));
        IF(@PRIVILEGIOS=1) THEN
            /*VERIFICAMOS SI EL USUARIO EXISTE EN EL SISTEMA*/
            SET @EXISTE_USUARIO = (SELECT FUNCT_EXISTE_USUARIO_SISTEMA(NIP_NUEVO));
            IF(@EXISTE_USUARIO=0) THEN

                /*EVALUAMOS QUE EL ROL EXISTE EN LA BD Y EXTRAEMOS EL ID DE ESTE*/
                SET @NROL_USUARIO=(SELECT ID_ROL_USUARIO FROM ROL_USUARIO 
                WHERE DESCRIPCION=NOMBRE_ROL_USUARIO_NUEVO LIMIT 1);
                /*SI NO NOS DEVUELVE NULL*/ 
                IF(@NROL_USUARIO IN(8)) THEN
                    INSERT INTO USUARIO_SISTEMA(NIP,NOMBRES,APELLIDOS,CORREO_INSTITUCIONAL,ID_ROL_USUARIO,
                    ID_ESTADO_USUARIO,FECHA_ULTIMO_ACCESO,ACCESO,NIP_ULT_USR_MODIFICADOR)
                    VALUES(NIP_NUEVO,NOMBRES_USR_NUEVO,APELLIDOS_USR_NUEVO,CORREO_INSTITUCIONAL_USR_NUEVO,
                    @NROL_USUARIO,1,NOW(),CONTRASENIA_USR_NUEVO,NIP_ULT_USR_MOD);
                ELSE
                SIGNAL SQLSTATE '20004' SET MESSAGE_TEXT = 'USTED NO TIENE PRIVILEGIOS PARA ESTA ACCION';
                END IF;
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20096' SET MESSAGE_TEXT = 'El usuario ya existe, sino lo observa en la tabla, comuníquese con el desarrollador';
                ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20099' SET MESSAGE_TEXT='¡Usted no tiene privilegios de Administrador!';
            ROLLBACK;
        END IF;
END;

COMMIT;
select * from usuario_sistema where nip=1751;
/*procedimiento almacenado para la actualización de datos de un usuario*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZAR_USUARIO_SISTEMA; 
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZAR_USUARIO_SISTEMA`(IN NIP_FIJO INT, IN NOMBRES_USR_EDITAR VARCHAR(100),
IN APELLIDOS_USR_EDITAR VARCHAR(100),IN CORREO_INSTITUCIONAL_USR_EDITAR VARCHAR(100),
IN NOMBRE_ROL_USUARIO_EDITAR VARCHAR(100),IN CONTRASENIA_USR_EDITAR VARCHAR(100),IN NIP_ULT_USR_MOD INT)
BEGIN
    /*variables para capturar los datos recuperados de la bd del usuario
    a través de un cursor*/
    DECLARE C_NIP INT(10);
    DECLARE C_NOMBRES VARCHAR(200);
    DECLARE C_APELLIDOS VARCHAR(200);
    DECLARE C_CORREO_INSTITUCIONAL VARCHAR(200);
    DECLARE C_ID_ROL_USUARIO INT(3);
    DECLARE C_ACCESO VARCHAR(200);
    DECLARE C_NIP_ULT_USR_MODIFICADOR INT(10);
    /*variable para cerrar el loop*/
    DECLARE FIN_LOOP INTEGER DEFAULT 0;
    /*creo un cursor para recuperar los datos del usuario a actualizar (datos)*/
    DECLARE CURSOR_SELECCION_USUARIO CURSOR FOR
    SELECT NIP,NOMBRES,APELLIDOS,CORREO_INSTITUCIONAL,ID_ROL_USUARIO,ACCESO,NIP_ULT_USR_MODIFICADOR
    FROM USUARIO_SISTEMA WHERE NIP=NIP_FIJO;

    /*VARIABLE PARA CONTROLAR EL FINAL DE RECORRIDO DEL CURSOR*/
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET FIN_LOOP=7; 
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20006' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de usuario";
    END;

    START TRANSACTION;

        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR(NIP_ULT_USR_MOD));
        IF(@PRIVILEGIOS=1) THEN
            /*EVALUAMOS QUE EL ROL EXISTE EN LA BD*/
            SET @NROL_USUARIO=(SELECT ID_ROL_USUARIO FROM ROL_USUARIO 
            WHERE DESCRIPCION=NOMBRE_ROL_USUARIO_EDITAR LIMIT 1);
            /*SI EL ID DE ROL DE USUARIO SE ENCUENTRA DENTRO DE LOS PERMITIDOS*/ 
            IF(@NROL_USUARIO IN(2,7,8)) THEN
                /*abrimos el cursor*/
                OPEN CURSOR_SELECCION_USUARIO;
                    CICLO: LOOP
                    FETCH CURSOR_SELECCION_USUARIO INTO C_NIP,C_NOMBRES, C_APELLIDOS,C_CORREO_INSTITUCIONAL,
                    C_ID_ROL_USUARIO,C_ACCESO, C_NIP_ULT_USR_MODIFICADOR;
                        /*comprobamos si llegamos al final de los registros obtenidos del cursor*/
                        IF FIN_LOOP = 7 THEN
                            LEAVE CICLO;
                        END IF;
                        /*HARÉ VALIDACIÓN DE CAMPO POR CAMPO PARA IDENTIFICAR SI VARIA EL VALOR ANTIGUO
                        DE CADA UNO CON EL NUEVO ENVIADO POR EL USUARIO DESDE LA VISTA*/
                        /*SI SE ACTUALIZA EL NOMBRE DEL USUARIO*/
                        IF(NOMBRES_USR_EDITAR<>C_NOMBRES) THEN
                            UPDATE USUARIO_SISTEMA SET NOMBRES=NOMBRES_USR_EDITAR WHERE NIP=NIP_FIJO;
                        END IF;
                        /*SI LOS APELLIDOS O APELLIDO SON O ES ACTUALIZADO*/
                        IF(APELLIDOS_USR_EDITAR<>C_APELLIDOS) THEN
                            UPDATE USUARIO_SISTEMA SET APELLIDOS=APELLIDOS_USR_EDITAR WHERE NIP=NIP_FIJO;
                        END IF;

                        /*SI EL CORREO ELECTRÓNICO ES CAMBIADO*/
                        IF(C_CORREO_INSTITUCIONAL<> CORREO_INSTITUCIONAL_USR_EDITAR) THEN    
                            UPDATE USUARIO_SISTEMA SET CORREO_INSTITUCIONAL=CORREO_INSTITUCIONAL_USR_EDITAR WHERE NIP=NIP_FIJO;
                        END IF;
                        /*SI EL ROL ES ALTERADO*/
                        IF(C_ID_ROL_USUARIO<>@NROL_USUARIO) THEN
                            UPDATE USUARIO_SISTEMA SET  ID_ROL_USUARIO=@NROL_USUARIO WHERE NIP=NIP_FIJO;
                        END IF;
                        /*SI LA CONTRASEÑA ES CAMBIADA*/
                        IF(C_ACCESO<>CONTRASENIA_USR_EDITAR) THEN
                            UPDATE USUARIO_SISTEMA SET ACCESO=CONTRASENIA_USR_EDITAR WHERE NIP=NIP_FIJO;
                        END IF;
                        /*DEBEMOS REGISTAR QUE USUARIO CON PRIVILEGIOS DE ADMIN 
                        FUE EL ÚLITMO EN EDITAR A OTRO USUARIO*/
                        IF(C_NIP_ULT_USR_MODIFICADOR<>NIP_ULT_USR_MOD) THEN
                            UPDATE USUARIO_SISTEMA SET NIP_ULT_USR_MODIFICADOR=C_NIP_ULT_USR_MODIFICADOR
                            WHERE NIP=NIP_FIJO;
                        END IF;         
                    END LOOP CICLO;
                CLOSE CURSOR_SELECCION_USUARIO;
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20001' SET MESSAGE_TEXT = 'ROL INEXISTENTE';
            ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20100' SET MESSAGE_TEXT = '¡USTED NO TIENE PRIVILEGIOS PARA RELIZAR ESTAS ACCIONES!';
        END IF;    
END;


/*Procedimiento Almacenado para la actualización de estado de un usuario*/
/*Recibe como parámetros el nip del usuario a modificar el estado, el estado nuevo (activo, inactivo)
y el NIP del usuario logueado que hace la modificación del estado*/
DROP PROCEDURE  IF EXISTS PA_ACTUALIZACION_ESTADO_USR;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZACION_ESTADO_USR`(IN NIP_FIJO INT, IN ESTADO_USUARIO INT,IN NIP_USR_RESP INT)
BEGIN
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20006' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de estado del usuario";
    END;

    START TRANSACTION;
        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR(NIP_USR_RESP));
        IF(@PRIVILEGIOS=1) THEN
            UPDATE USUARIO_SISTEMA SET ID_ESTADO_USUARIO=ESTADO_USUARIO WHERE NIP=NIP_FIJO;
            UPDATE USUARIO_SISTEMA SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE NIP=NIP_FIJO;
        ELSE
            SIGNAL SQLSTATE '20007' SET MESSAGE_TEXT = "¡Usted no tiene privilegios de Administrador!";
            ROLLBACK;
        END IF;
    COMMIT;
    END;


/*Procedimiento almacenado para guardar el último ingreso al sistema por parte del usuario*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZACION_ULTIMO_LOGIN; 
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZACION_ULTIMO_LOGIN`(IN NIP_FIJO INT,IN NIP_USR_RESP INT)
BEGIN
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20005' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20006' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de usuario";
    END;

    START TRANSACTION;
            UPDATE USUARIO_SISTEMA SET FECHA_ULTIMO_ACCESO=NOW() WHERE NIP=NIP_FIJO;
            UPDATE USUARIO_SISTEMA SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE NIP=NIP_FIJO;
    COMMIT;

END// DELIMITER;




/*PROCEDIMIENTO ALMACENAOD PARA DAR DE BAJA A UN USUARIO DEL SISTEMA*/
DROP PROCEDURE  IF EXISTS PA_BAJA_USR_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_BAJA_USR_SISTEMA`(IN NIP_FIJO INT,IN NIP_USR_RESP INT)
BEGIN
    /*Variable para determinar si el usuario que quiere hacer el UPDATE 
    existe en la bd, y tiene rol de administrador*/
    DECLARE VALIDAR_ROL INT;
    /*Variable para recuperar el id del estado "baja"*/
    DECLARE ID_ESTADO_BAJA INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20041' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de BAJA DEL USUARIO";
    END;

    START TRANSACTION;
        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR(NIP_USR_RESP));
        IF(@PRIVILEGIOS=1) THEN
            /*RECUPERO EL ID DEL ESTADO "BAJA" CON UNA FUNCIÓN*/
            SET ID_ESTADO_BAJA=(SELECT FUNCT_DEVOLVER_ESTADO_USR_BAJA());
            /*valido si existe el usuario responsable de la gestión,
            SI existe y tiene rol de admin y está activo, devuelve 1, sino 0*/
            SET VALIDAR_ROL=(SELECT FUNCT_EXISTE_USR (NIP_USR_RESP));
            IF(VALIDAR_ROL=1) THEN
                UPDATE USUARIO_SISTEMA SET ID_ESTADO_USUARIO=ID_ESTADO_BAJA WHERE NIP=NIP_FIJO;
                UPDATE USUARIO_SISTEMA SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE NIP=NIP_FIJO;
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20042' SET MESSAGE_TEXT = "El usuario no tiene privilegios, o no existe
                en la base de datos o está inactivo";
                ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20101' SET MESSAGE_TEXT="¡Usted no tiene privilegios para realizar esta acción!";
        END IF;
    END;








/*******************************************************************************/
                        /*FUNCIONES CRUD USUARIO */
/*******************************************************************************/

/*FUNCION PARA SABER  el rol de un usuario en específico*/
DROP FUNCTION IF EXISTS FUNCT_NOMBRE_ROL_USUARIO;
DELIMITER //
CREATE FUNCTION FUNCT_NOMBRE_ROL_USUARIO(NIP_URS INT) RETURNS VARCHAR(100)
BEGIN
    DECLARE DESCRIPCION_ROL VARCHAR(100);
    /*Valido que el usuario exista, y esté activo*/
    SET DESCRIPCION_ROL=(SELECT RU.DESCRIPCION FROM ROL_USUARIO RU
    INNER JOIN USUARIO_SISTEMA US ON 
    RU.ID_ROL_USUARIO = US.ID_ROL_USUARIO
    WHERE US.NIP=NIP_URS AND  US.ID_ESTADO_USUARIO=1);

    IF(DESCRIPCION_ROL!='') THEN
        RETURN DESCRIPCION_ROL;
    ELSE
        SIGNAL SQLSTATE '20007' SET MESSAGE_TEXT = "EL ROL O USUARIO 
        NO EXISTEN EN EL SISTEMA";
    END IF;
END// DELIMITER;


/*funcion para validar que existe el usuario que pretende modificar el estado de otro,
,si existe, devuelve 1 y sino 0, además que tenga privilegios de administrador*/
DROP FUNCTION  IF EXISTS FUNCT_EXISTE_USR;
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_USR(NIP_RESP INT) RETURNS INT
    BEGIN 
        /*Variable en la cual vamos a devolver el resultado de un count
        que realizaré para validar si un usuario existe en la bd y está activo*/
        DECLARE CONTAR_RESULTADO INT;
        /*Variable para capturar el rol de un usuario*/
        DECLARE ROL_USR_RESP VARCHAR(100);
        /*verifico si el usuario que quiere modificar el estado de otro,
        existe en la bd*/
        SET CONTAR_RESULTADO=(SELECT COUNT(*) FROM USUARIO_SISTEMA WHERE NIP=NIP_RESP AND ID_ESTADO_USUARIO<>3 AND ID_ESTADO_USUARIO<>2);
        /*Valido que el usuario tenga rol de admin*/
        SET ROL_USR_RESP=(SELECT FUNCT_NOMBRE_ROL_USUARIO(NIP_RESP));
        /*realizo una condicional para validar que el usuario exista en la bd y tenga
        rol de usuario para hacer el proceso*/
        IF(CONTAR_RESULTADO=1 AND ROL_USR_RESP="ADMINISTRADOR") THEN
            RETURN 1;
        ELSE
            RETURN 0;
        END IF;  
END// DELIMITER;



/*FUNCION PARA DEVOLVER EL ID DEL TIPO DE "ESTADO BAJA"*/
DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ESTADO_USR_BAJA;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ESTADO_USR_BAJA() RETURNS INT(03)
BEGIN
    DECLARE ID_ESTADO_BAJA INT;
    SET ID_ESTADO_BAJA=(SELECT ID_ESTADO_USUARIO FROM ESTADO_USUARIO WHERE DESCRIPCION="BAJA");

    IF(ID_ESTADO_BAJA!='') THEN
        RETURN ID_ESTADO_BAJA;
    ELSE
        SIGNAL SQLSTATE '20007' SET MESSAGE_TEXT = "ERROR AL BUSCAR EL ESTADO DE USUARIO, CONTACTE AL DBA";
    END IF;
END// DELIMITER;


/*función para determinar si existe ya un usuario con el nip en el sistema*/
DROP FUNCTION IF EXISTS FUNCT_EXISTE_USUARIO_SISTEMA;
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_USUARIO_SISTEMA(NIP_NUEVO INT) RETURNS INT
BEGIN
    SET @VERIFICAR_NIP=(SELECT COUNT(*) FROM USUARIO_SISTEMA WHERE NIP=NIP_NUEVO);

    IF(@VERIFICAR_NIP>0) THEN
        RETURN @VERIFICAR_NIP;
    ELSE
        RETURN @VERIFICAR_NIP;
    END IF;






/*******************************************************************************/
                        /*TRIGGERS CRUD USUARIO */
/*******************************************************************************/
DROP TRIGGER IF EXISTS AI_USUARIO_SISTEMA; 
/*Trigger para registar la creación de un nuevo usuario*/
CREATE DEFINER=`root`@`localhost` TRIGGER AI_USUARIO_SISTEMA
    AFTER INSERT ON USUARIO_SISTEMA
    FOR EACH ROW
    BEGIN
      INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,ID_TIPO_MOVIMIENTO,
      NIP_USUARIO_RESP,FECHA_MOVIMIENTO)
      VALUES(new.NIP,1,new.NIP_ULT_USR_MODIFICADOR,NOW());
  	END



/*Trigger para registar la actualización de los datos de un usuario*/
DROP TRIGGER IF EXISTS AU_USUARIO_SISTEMA; 

CREATE DEFINER=`root`@`localhost` TRIGGER AU_USUARIO_SISTEMA
AFTER UPDATE ON USUARIO_SISTEMA
FOR EACH ROW
BEGIN 
    /*Variable para capturar el nombre del estado del usuario
    que se está modificando*/    
    DECLARE ESTADO_USR VARCHAR(100);
    /*en las comparaciones adicionales new.variable<>old.variable
    estamos registrando únicamente los movimientos cuando las variables
    son alteradas de valor, es decir, cambian.*/
    IF(NEW.NOMBRES IS NOT NULL AND NEW.NOMBRES<>OLD.NOMBRES) THEN
        
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)
            VALUES(NEW.NIP,2,OLD.NOMBRES,new.NOMBRES,2,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.APELLIDOS IS NOT NULL AND NEW.APELLIDOS<>OLD.APELLIDOS) THEN
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.APELLIDOS,NEW.APELLIDOS,3,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.CORREO_INSTITUCIONAL IS NOT NULL AND NEW.CORREO_INSTITUCIONAL<>OLD.CORREO_INSTITUCIONAL) THEN
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.CORREO_INSTITUCIONAL,NEW.CORREO_INSTITUCIONAL,
            4,NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.ID_ROL_USUARIO IS NOT NULL AND NEW.ID_ROL_USUARIO<>OLD.ID_ROL_USUARIO) THEN
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.ID_ROL_USUARIO,NEW.ID_ROL_USUARIO,5,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.ID_ESTADO_USUARIO IS NOT NULL AND NEW.ID_ESTADO_USUARIO<>OLD.ID_ESTADO_USUARIO) THEN
        /*veo a que valor coincide el nuevo estado del usuario*/
        SELECT DESCRIPCION INTO ESTADO_USR FROM ESTADO_USUARIO 
        WHERE ID_ESTADO_USUARIO=NEW.ID_ESTADO_USUARIO LIMIT 1;
        /*si el usuario de estar inactivo pasa a activo*/
        IF (ESTADO_USR='ACTIVO') THEN
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.ID_ESTADO_USUARIO,NEW.ID_ESTADO_USUARIO,6,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        /*SI EL USUARIO PASARÁ A INACTIVO, EN LA BITÁCORA REGISTRAMOS QUE EL TIPO DE 
        MOVIMIENTO = 3 QUE PERTENECE A "BAJA" DEL SISTEMA (NO ES BORRADO)*/    
        ELSEIF(ESTADO_USR='INACTIVO') THEN
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.ID_ESTADO_USUARIO,NEW.ID_ESTADO_USUARIO,6,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
            /*si el usuario pasa de cualquiera de los dos estados
            anteriores a estado de baja (no será visible más por el usuario, solo por el dba)*/
            ELSEIF(ESTADO_USR='BAJA') THEN
            INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,3,OLD.ID_ESTADO_USUARIO,NEW.ID_ESTADO_USUARIO,6,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        END IF;
    ELSEIF(NEW.FECHA_ULTIMO_ACCESO IS NOT NULL AND NEW.FECHA_ULTIMO_ACCESO<>OLD.FECHA_ULTIMO_ACCESO) THEN
        INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
        ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
        NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
        VALUES(NEW.NIP,2,OLD.FECHA_ULTIMO_ACCESO,NEW.FECHA_ULTIMO_ACCESO,7,
        NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.NIP_ULT_USR_MODIFICADOR IS NOT NULL  AND NEW.NIP_ULT_USR_MODIFICADOR<>OLD.NIP_ULT_USR_MODIFICADOR) THEN
        INSERT INTO BITACORA_USUARIO_SISTEMA(NIP_USUARIO_AFECTADO,
        ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
        NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
        VALUES(NEW.NIP,2,OLD.NIP_ULT_USR_MODIFICADOR,NEW.NIP_ULT_USR_MODIFICADOR,8,
        NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    END IF;
END




/*Regla 3:
    Será posible la inserción, actualización y baja de Proveedores de productos
    dentro del sistema
*/
/*******************************************************************************/
                        /*     CRUD PROVEEDOR      */
/*******************************************************************************/

/*******************************************************************************/
                        /*    VISTAS CRUD PROVEEDOR      */
/*******************************************************************************/

DROP VIEW IF EXISTS VISTA_CRUD_PROVEEDOR;
CREATE VIEW VISTA_CRUD_PROVEEDOR
(ID_PROVEEDOR,NOMBRE,DIRECCION,TELEFONO,ESTADO_PROVEEDOR)
AS
(SELECT P.ID_PROVEEDOR,P.NOMBRE,P.DIRECCION,P.TELEFONO,EP.DESCRIPCION
FROM PROVEEDOR P INNER JOIN ESTADO_PROVEEDOR EP
ON P.ID_ESTADO_PROVEEDOR=EP.ID_ESTADO_PROVEEDOR AND EP.DESCRIPCION<>'BAJA');


/*******************************************************************************/
                        /*    PROCEDIMIENTOS ALMACENADOS CRUD PROVEEDOR      */
/*******************************************************************************/


/*CREACIÓN DE PROVEEDOR*/
USE PRUEBA_BODEGA_TFM;
DROP PROCEDURE IF EXISTS PA_CREAR_PROVEEDOR;
DELIMITER //
CREATE PROCEDURE PA_CREAR_PROVEEDOR
(
    NOMBRE_PROV_NUEVO VARCHAR(200),DIRECCION_PROV_NUEVO VARCHAR(300),
    TELEFONO_NUEVO VARCHAR(20), NIP_USR_RESP INT(10)
)
BEGIN
 
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20022' SET MESSAGE_TEXT = "ERROR DURANTE
         LA EJECUCION DEL PA_CREAR_PROVEEDOR";
    END;
    START TRANSACTION;
        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0 sin importar el rol*/
        SET @EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(@EXISTE_USUARIO_ACTIVO=1) THEN
            
                INSERT INTO PROVEEDOR (NOMBRE,DIRECCION,TELEFONO,ID_ESTADO_PROVEEDOR,FECHA_CREACION,
                NIP_ULT_USR_MODIFICADOR)
                VALUES(NOMBRE_PROV_NUEVO,DIRECCION_PROV_NUEVO,TELEFONO_NUEVO,1,NOW(),NIP_USR_RESP);
        ELSE
            SIGNAL SQLSTATE '20110' SET MESSAGE_TEXT ="ERROR AL CREAR PROVEEDOR, COMUNÍQUESE CON EL DESARROLLADOR";
            ROLLBACK;
        END IF;
    COMMIT;
END;

/*ACTUALIZACION PROVEEDOR*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZAR_PROVEEDOR;
DELIMITER // 
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZAR_PROVEEDOR`(IN ID_PROVEEDOR_FIJO INT(10), 
IN NOMBRE_PROVEEDOR VARCHAR(200),IN DIRECCION_PROVEEDOR VARCHAR(300),IN TELEFONO_PROVEEDOR VARCHAR(20),NIP_USR_MODIFICADOR INT(10)
)
BEGIN
    /*variables para capturar los datos recuperados de la bd del Proveedor
    a través de un cursor*/
    DECLARE C_ID_PROVEEDOR INT(10);
    DECLARE C_NOMBRE VARCHAR(200);
    DECLARE C_DIRECCION VARCHAR(300);
    DECLARE C_TELEFONO VARCHAR(20);
    DECLARE C_ID_PROVEEDOR_ULT_USR_MODIFICADOR INT(10);
    /*variable para cerrar el loop*/
    DECLARE FIN_LOOP INTEGER DEFAULT 0;
    /*creo un cursor para recuperar los datos del usuario a actualizar (datos)*/
    DECLARE CURSOR_SELECCION_PROVEEDOR CURSOR FOR
    SELECT ID_PROVEEDOR,NOMBRE,DIRECCION,TELEFONO,NIP_ULT_USR_MODIFICADOR
    FROM PROVEEDOR WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;

    /*VARIABLE PARA CONTROLAR EL FINAL DE RECORRIDO DEL CURSOR*/
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET FIN_LOOP=1; 

    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20024' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION de PROVEEDOR, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20025' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de PROVEEDOR";
    END;

    START TRANSACTION;
        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0 sin importar el rol*/
        SET @EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_MODIFICADOR));
        IF(@EXISTE_USUARIO_ACTIVO=1) THEN
            /*abrimos el cursor*/
            OPEN CURSOR_SELECCION_PROVEEDOR;
                CICLO: LOOP
                FETCH CURSOR_SELECCION_PROVEEDOR INTO C_ID_PROVEEDOR,C_NOMBRE, C_DIRECCION,C_TELEFONO, 
                C_ID_PROVEEDOR_ULT_USR_MODIFICADOR;
                    /*comprobamos si llegamos al final de los registros obtenidos del cursor*/
                    IF FIN_LOOP = 1 THEN
                        LEAVE CICLO;
                    END IF;
                    /*HARÉ VALIDACIÓN DE CAMPO POR CAMPO PARA IDENTIFICAR SI VARIA EL VALOR ANTIGUO
                    DE CADA UNO CON EL NUEVO ENVIADO POR EL USUARIO DESDE LA VISTA*/
                    /*SI SE ACTUALIZA EL NOMBRE DEL USUARIO*/
                    IF(NOMBRE_PROVEEDOR<>C_NOMBRE) THEN
                        UPDATE PROVEEDOR SET NOMBRE=NOMBRE_PROVEEDOR WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
                    END IF;
                    /*SI LA DIRECCION  ES Actualizada*/
                    IF(DIRECCION_PROVEEDOR<>C_DIRECCION) THEN
                        UPDATE PROVEEDOR SET DIRECCION=DIRECCION_PROVEEDOR WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
                    END IF;

                    /*SI EL TELEFONO ES CAMBIADO*/
                    IF(TELEFONO_PROVEEDOR<> C_TELEFONO) THEN    
                        UPDATE PROVEEDOR SET TELEFONO=TELEFONO_PROVEEDOR WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
                    END IF;
                    IF(C_ID_PROVEEDOR_ULT_USR_MODIFICADOR<>NIP_USR_MODIFICADOR) THEN
                        UPDATE PROVEEDOR SET NIP_ULT_USR_MODIFICADOR=NIP_USR_MODIFICADOR
                    WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
                    END IF;         
                END LOOP CICLO;
            CLOSE CURSOR_SELECCION_PROVEEDOR;
            COMMIT;   
        ELSE
            SIGNAL SQLSTATE '20111' SET MESSAGE_TEXT ="ERROR AL ACTUALIZAR PROVEEDOR, COMUNÍQUESE CON EL DESARROLLADOR";
            ROLLBACK;
        END IF;
        
END;





/*actualización de estado de proveedor*/
/*Procedimiento Almacenado para la actualización de estado de un usuario*/
/*Recibe como parámetros el id del proveedor a modificar el estado, el estado nuevo (activo, inactivo)
y el NIP del usuario logueado que hace la modificación del estado*/
DROP PROCEDURE  IF EXISTS PA_ACTUALIZACION_ESTADO_PROVEEDOR;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZACION_ESTADO_PROVEEDOR`(IN ID_PROVEEDOR_FIJO INT, IN ESTADO_PROVEEDOR INT,IN NIP_USR_RESP INT)
BEGIN

    /*VARIABLE PARA CAPTURAR EL RESULTADO DE EVALUACIÓN DE ESTADO Y VERIFICACION DE USUARIO EN EL SISTEMA*/
    DECLARE EXISTE_USUARIO_ACTIVO INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20026' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION DE ESTADO, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20027' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de estado del usuario";
    END;

    START TRANSACTION;
        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0*/
        SET EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(EXISTE_USUARIO_ACTIVO=1) THEN
            UPDATE PROVEEDOR SET ID_ESTADO_PROVEEDOR=ESTADO_PROVEEDOR WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
            UPDATE PROVEEDOR SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
        ELSE
        SIGNAL SQLSTATE '20028' SET MESSAGE_TEXT = "EL USUARIO ESTÁ INACTIVO O NO EXISTE EN EL SISTEMA";
        END IF;
    COMMIT;
    END;


/*PROCEDIMIENTO ALMACENAOD PARA DAR DE BAJA A UN PROVEEDOR DEL SISTEMA*/
DROP PROCEDURE  IF EXISTS PA_BAJA_PROVEEDOR_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_BAJA_PROVEEDOR_SISTEMA`(IN ID_PROVEEDOR_FIJO INT,IN NIP_USR_RESP INT)
BEGIN
    /*Variable para recuperar el id del estado "baja"*/
    DECLARE ID_ESTADO_BAJA INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20047' SET MESSAGE_TEXT = "ERROR AL DAR DE BAJA AL PROVEEDOR, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20048' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de DAR DE BAJA AL PROVEEDOR DE SISTEMA";
    END;

    START TRANSACTION;

        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0 sin importar el rol*/
        SET @EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(@EXISTE_USUARIO_ACTIVO=1) THEN
            /*RECUPERO EL ID DEL ESTADO "BAJA" CON UNA FUNCIÓN*/
            SET ID_ESTADO_BAJA=(SELECT FUNCT_DEVOLVER_ESTADO_PROVEEDOR_BAJA());
            IF(!ISNULL(ID_ESTADO_BAJA)) THEN
                UPDATE PROVEEDOR SET ID_ESTADO_PROVEEDOR=ID_ESTADO_BAJA WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
                UPDATE PROVEEDOR SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE ID_PROVEEDOR=ID_PROVEEDOR_FIJO;
            ELSE
            SIGNAL SQLSTATE '20049' SET MESSAGE_TEXT = "ESTADO DE TRANSACCION INDEFINIDO EN EL MÓDULO DE PROVEEDOR";
            END IF;
        ELSE
            SIGNAL SQLSTATE '20113' SET MESSAGE_TEXT ="ERROR AL CREAR PROVEEDOR, COMUNÍQUESE CON EL DESARROLLADOR";
            ROLLBACK;
        END IF; 
        COMMIT;
    END;




/*******************************************************************************/
                        /*   FUNCIONES  CRUD PROVEEDOR      */
/*******************************************************************************/

/*funcion para validar que existe el usuario que pretende modificar el estado de otro,
,si existe Y ESTÁ ACTIVO, devuelve 1 y sino 0,*/
DROP FUNCTION IF EXISTS FUNCT_EXISTE_USR_SISTEMA_ACTIVO; 

CREATE DEFINER=`root`@`localhost` FUNCTION `FUNCT_EXISTE_USR_SISTEMA_ACTIVO`(NIP_RESP INT) RETURNS int(11)
BEGIN 
        /*Variable en la cual vamos a devolver el resultado de un count
        que realizaré para validar si un usuario existe en la bd y está activo*/
        DECLARE CONTAR_RESULTADO INT;
        /*verifico si el usuario que quiere modificar el estado de otro,
        existe en la bd Y ESTÁ ACTIVO*/
        SET CONTAR_RESULTADO=(SELECT COUNT(*) FROM USUARIO_SISTEMA WHERE NIP=NIP_RESP AND ID_ESTADO_USUARIO<>3 AND ID_ESTADO_USUARIO<>2);
        IF(CONTAR_RESULTADO=1) THEN
            RETURN 1;
        ELSE
            RETURN 0;
        END IF;
END

/*FUNCION QUE DEVUELVE EL ID DEL ESTADO CUANDO ES DE "BAJA" EN LA TABLA ESTADO_PROVEEDOR*/
DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ESTADO_PROVEEDOR_BAJA;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ESTADO_PROVEEDOR_BAJA() RETURNS INT(03)
BEGIN
    DECLARE ID_ESTADO_BAJA INT;
    SET ID_ESTADO_BAJA=(SELECT ID_ESTADO_PROVEEDOR FROM ESTADO_PROVEEDOR WHERE DESCRIPCION="BAJA");

    IF(ID_ESTADO_BAJA!='') THEN
        RETURN ID_ESTADO_BAJA;
    ELSE
        SIGNAL SQLSTATE '20046' SET MESSAGE_TEXT = "ERROR AL BUSCAR EL ESTADO DE USUARIO, CONTACTE AL DBA";
    END IF;
END// DELIMITER;





/*******************************************************************************/
                        /*TRIGGERS CRUD PROVEEDOR */
/*******************************************************************************/

DROP TRIGGER IF EXISTS AI_PROVEEDOR; 
/*Trigger para registar la creación de un nuevo usuario*/
CREATE TRIGGER AI_PROVEEDOR
    AFTER INSERT ON PROVEEDOR
    FOR EACH ROW
    BEGIN
      INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,ID_TIPO_MOVIMIENTO,NIP_USUARIO_RESP,FECHA_MOVIMIENTO)
      VALUES(new.ID_PROVEEDOR,1,new.NIP_ULT_USR_MODIFICADOR,NOW());
  	END
COMMIT;


/*Trigger para registar la actualización de los datos de un proveedor*/
DROP TRIGGER IF EXISTS AU_PROVEEDOR; 

CREATE DEFINER=`root`@`localhost` TRIGGER AU_PROVEEDOR
AFTER UPDATE ON PROVEEDOR
FOR EACH ROW
BEGIN 
    /*Variable para capturar el nombre del estado del PROVEEDOR
    que se está modificando*/    
    DECLARE ESTADO_PROVEEDOR VARCHAR(100);
    /*en las comparaciones adicionales new.variable<>old.variable
    estamos registrando únicamente los movimientos cuando las variables
    son alteradas de valor, es decir, cambian.*/
    IF(NEW.NOMBRE IS NOT NULL AND NEW.NOMBRE<>OLD.NOMBRE) THEN
        
            INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)
            VALUES(NEW.ID_PROVEEDOR,2,OLD.NOMBRE,new.NOMBRE,2,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.DIRECCION IS NOT NULL AND NEW.DIRECCION<>OLD.DIRECCION) THEN
            INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_PROVEEDOR,2,OLD.DIRECCION,NEW.DIRECCION,3,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.TELEFONO IS NOT NULL AND NEW.TELEFONO<>OLD.TELEFONO) THEN
            INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_PROVEEDOR,2,OLD.TELEFONO,NEW.TELEFONO,
            4,NEW.NIP_ULT_USR_MODIFICADOR,NOW());

    ELSEIF(NEW.ID_ESTADO_PROVEEDOR IS NOT NULL AND NEW.ID_ESTADO_PROVEEDOR<>OLD.ID_ESTADO_PROVEEDOR) THEN
        /*veo a que valor coincide el nuevo estado del usuario*/
        SELECT DESCRIPCION INTO ESTADO_PROVEEDOR FROM ESTADO_PROVEEDOR 
        WHERE ID_ESTADO_PROVEEDOR=NEW.ID_ESTADO_PROVEEDOR LIMIT 1;
        /*si el usuario de estar inactivo pasa a activo*/
        IF (ESTADO_PROVEEDOR='ACTIVO') THEN
            INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_PROVEEDOR,2,OLD.ID_ESTADO_PROVEEDOR,NEW.ID_ESTADO_PROVEEDOR,5,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        ELSEIF(ESTADO_PROVEEDOR='INACTIVO') THEN
            INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_PROVEEDOR,2,OLD.ID_ESTADO_PROVEEDOR,NEW.ID_ESTADO_PROVEEDOR,5,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        /*SI EL USUARIO PASARÁ A BAJA (BORRADO A LA VISTA DEL USUARIO), EN LA BITÁCORA REGISTRAMOS QUE EL TIPO DE 
        MOVIMIENTO = 3  (NO ES BORRADO)*/    
        ELSEIF(ESTADO_PROVEEDOR='BAJA') THEN
            INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_PROVEEDOR,3,OLD.ID_ESTADO_PROVEEDOR,NEW.ID_ESTADO_PROVEEDOR,5,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        END IF;
    ELSEIF(NEW.NIP_ULT_USR_MODIFICADOR IS NOT NULL  AND NEW.NIP_ULT_USR_MODIFICADOR<>OLD.NIP_ULT_USR_MODIFICADOR) THEN
        INSERT INTO BITACORA_PROVEEDOR(ID_PROVEEDOR,
        ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
        NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
        VALUES(NEW.ID_PROVEEDOR,2,OLD.NIP_ULT_USR_MODIFICADOR,NEW.NIP_ULT_USR_MODIFICADOR,7,
        NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    END IF;
END;





/*Regla 4:
    Será posible la inserción, actualización y baja de categorias de productos
    dentro del sistema
*/
/*******************************************************************************/
                        /*     CRUD CATEGORIA DE PRODUCTOS     */
/*******************************************************************************/


/*******************************************************************************/
                        /*    VISTAS CRUD CATEGORIA      */
/*******************************************************************************/

DROP VIEW IF EXISTS VISTA_CRUD_CATEGORIA;
CREATE VIEW VISTA_CRUD_CATEGORIA
(ID_TIPO_PRODUCTO,DESCRIPCION_TIPO_PRODUCTO,ESTADO_TIPO_PRODUCTO)
AS
(SELECT TCPR.ID_TIPO_PRODUCTO,TCPR.DESCRIPCION AS DESCRIPCION_TP_PRODUCTO,ETP.DESCRIPCION
FROM TIPO_PRODUCTO TCPR INNER JOIN ESTADO_TIPO_PRODUCTO ETP
ON TCPR.ID_ESTADO_TIPO_PRODUCTO=ETP.ID_ESTADO_TIPO_PRODUCTO AND ETP.DESCRIPCION<>'BAJA');


/*******************************************************************************/
                        /*    PROCEDIMIENTOS ALMACENADOS CRUD CATEGORIAS DE PRODUCTOS */
/*******************************************************************************/
/*Procedimiento almacenado para la creación de una categoría,
vamos a recibir como parámetro de entrada un nombre de categoría*/
/*PROCEDIMIENTO ALMACENADO PARA LA CREACION 
 DE ALGUNA CATEGORÍA, POR REGLA DEL NEGOCIO NO SE PERMITIRÁ
 MODIFICARLE EL ID UNA VEZ CREADA*/

USE PRUEBA_BODEGA_TFM;
DROP PROCEDURE IF EXISTS PA_CREACION_CATEGORIA;
DELIMITER //
CREATE PROCEDURE PA_CREACION_CATEGORIA(IN ALIAS VARCHAR(100),IN NIP_RESPONSABLE INT)
BEGIN
/*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20030' SET MESSAGE_TEXT = "ERROR AL GRABAR LA CREACION  DE CATEGORIA, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20027' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de CREACION DE CATEGORIA";
    END;
    START TRANSACTION;
        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0 sin importar el rol*/
        SET @EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_RESPONSABLE));
        IF(@EXISTE_USUARIO_ACTIVO=1) THEN
              IF(!ISNULL(ALIAS)) THEN
                    INSERT INTO TIPO_PRODUCTO(DESCRIPCION,ID_ESTADO_TIPO_PRODUCTO,NIP_ULT_USR_MODIFICADOR) VALUES(ALIAS,1,NIP_RESPONSABLE);
              ELSE
                    SIGNAL SQLSTATE '20032' SET MESSAGE_TEXT = "DEBE INTRODUCIR UN NOMBRE PARA LA CATEGORIA";
              END IF;
        ELSE
            SIGNAL SQLSTATE '20120' SET MESSAGE_TEXT ="ERROR AL CREAR CATEGORIA, COMUNÍQUESE CON EL DESARROLLADOR";
            ROLLBACK;
        END IF;
        COMMIT;     
END// DELIMITER;



/*ACTUALIZACION TIPO_PRODUCTO*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZAR_CATEGORIA;
DELIMITER // 
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZAR_CATEGORIA`(IN ID_TIPO_PRODUCTO_FIJO INT(03), 
IN DESCRIPCION_CAMBIADA VARCHAR(100),NIP_USR_MODIFICADOR INT(10)
)
BEGIN
    /*variables para capturar los datos recuperados de la bd del TIPO_PRODUCTO
    a través de un cursor*/
    DECLARE C_ID_TIPO_PRODUCTO INT(03);
    DECLARE C_DESCRIPCION VARCHAR(100);
    DECLARE C_ID_TIPO_PRODUCTO_ULT_USR_MODIFICADOR INT(10);
    /*variable para cerrar el loop*/
    DECLARE FIN_LOOP INTEGER DEFAULT 0;
    /*creo un cursor para recuperar los datos del usuario a actualizar (datos)*/
    DECLARE CURSOR_SELECCION_TIPO_PRODUCTO CURSOR FOR
    SELECT ID_TIPO_PRODUCTO,DESCRIPCION,NIP_ULT_USR_MODIFICADOR
    FROM TIPO_PRODUCTO WHERE ID_TIPO_PRODUCTO=ID_TIPO_PRODUCTO_FIJO;

    /*VARIABLE PARA CONTROLAR EL FINAL DE RECORRIDO DEL CURSOR*/
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET FIN_LOOP=1; 

    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20037' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION de CATEGORIA, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20038' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de CATEOGORIA";
    END;

    START TRANSACTION;
    /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0 sin importar el rol*/
        SET @EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_MODIFICADOR));
        IF(@EXISTE_USUARIO_ACTIVO=1) THEN
        ELSE
            SIGNAL SQLSTATE '20110' SET MESSAGE_TEXT ="ERROR AL CREAR PROVEEDOR, COMUNÍQUESE CON EL DESARROLLADOR";
            ROLLBACK;
        END IF;
        
        /*abrimos el cursor*/
        OPEN CURSOR_SELECCION_TIPO_PRODUCTO;
            CICLO: LOOP
            FETCH CURSOR_SELECCION_TIPO_PRODUCTO INTO C_ID_TIPO_PRODUCTO,C_DESCRIPCION,
            C_ID_TIPO_PRODUCTO_ULT_USR_MODIFICADOR;
                /*comprobamos si llegamos al final de los registros obtenidos del cursor*/
                IF FIN_LOOP = 1 THEN
                    LEAVE CICLO;
                END IF;
                /*HARÉ VALIDACIÓN DE CAMPO POR CAMPO PARA IDENTIFICAR SI VARIA EL VALOR ANTIGUO
                DE CADA UNO CON EL NUEVO ENVIADO POR EL USUARIO QUE QUIERE ACTUALIZAR LOS DATOS*/
                /*SI SE ACTUALIZA LA DESCRIPCION DE LA CATEGORIA*/
                IF(DESCRIPCION_CAMBIADA<>C_DESCRIPCION) THEN
                    UPDATE TIPO_PRODUCTO SET DESCRIPCION=DESCRIPCION_CAMBIADA WHERE ID_TIPO_PRODUCTO=ID_TIPO_PRODUCTO_FIJO;
                END IF;
                IF(C_ID_TIPO_PRODUCTO_ULT_USR_MODIFICADOR<>NIP_USR_MODIFICADOR) THEN
                    UPDATE TIPO_PRODUCTO SET NIP_ULT_USR_MODIFICADOR=NIP_USR_MODIFICADOR
                WHERE ID_TIPO_PRODUCTO=ID_TIPO_PRODUCTO_FIJO;
                END IF;         
            END LOOP CICLO;
        CLOSE CURSOR_SELECCION_TIPO_PRODUCTO;
    COMMIT;   
END;



/*PROCEDIMIENTO ALMACENADO PARA LA ACTUALIZACIÓN DE ESTADO
 DE ALGUNA CATEGORÍA*/
 /*Procedimiento Almacenado para la actualización de estado de una categoria de productos*/
/*Recibe como parámetros el id de la categoria a modificar el estado, el estado nuevo (activo, inactivo)
y el NIP del usuario logueado que hace la modificación del estado*/
DROP PROCEDURE  IF EXISTS PA_ACTUALIZACION_ESTADO_CATEGORIA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZACION_ESTADO_CATEGORIA`(IN ID_CATEGORIA_FIJA INT, IN ESTADO_CATEGORIA INT,IN NIP_USR_RESP INT)
BEGIN

    /*VARIABLE PARA CAPTURAR EL RESULTADO DE EVALUACIÓN DE ESTADO Y VERIFICACION DE USUARIO EN EL SISTEMA*/
    DECLARE EXISTE_USUARIO_ACTIVO INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20033' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION DE CATEGORIA, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20034' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de estado CATEGORIA DE PRODUCTOS";
    END;

    START TRANSACTION;
        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0*/
        SET EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(EXISTE_USUARIO_ACTIVO=1) THEN
            UPDATE TIPO_PRODUCTO SET ID_ESTADO_TIPO_PRODUCTO=ESTADO_CATEGORIA WHERE ID_TIPO_PRODUCTO=ID_CATEGORIA_FIJA;
            UPDATE TIPO_PRODUCTO SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE ID_TIPO_PRODUCTO=ID_CATEGORIA_FIJA;
        ELSE
        SIGNAL SQLSTATE '20035' SET MESSAGE_TEXT = "EL USUARIO ESTÁ INACTIVO O NO EXISTE EN EL SISTEMA";
        END IF;
    COMMIT;
    END;




/*PROCEDIMIENTO ALMACENAOD PARA DAR DE BAJA A UNA CATEGORIA DEL SISTEMA*/
DROP PROCEDURE  IF EXISTS PA_BAJA_CATEGORIA_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_BAJA_CATEGORIA_SISTEMA`(IN ID_CATEGORIA_FIJO INT,IN NIP_USR_RESP INT)
BEGIN
    /*Variable para recuperar el id del estado "baja" De la tabla tipo estado categoria*/
    DECLARE ID_ESTADO_BAJA INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20060' SET MESSAGE_TEXT = "ERROR AL DAR DE BAJA A LA CATEGORIA, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20061' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de DAR DE BAJA A LA CATEGORIA DE SISTEMA";
    END;

    START TRANSACTION;

        /*RECUPERO EL ID DEL ESTADO "BAJA" CON UNA FUNCIÓN*/
         /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0*/
        SET @EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(@EXISTE_USUARIO_ACTIVO=1) THEN
            SET ID_ESTADO_BAJA=(SELECT FUNCT_DEVOLVER_ESTADO_CATEGORIA_BAJA());
            IF(!ISNULL(ID_ESTADO_BAJA)) THEN
                UPDATE TIPO_PRODUCTO SET ID_ESTADO_TIPO_PRODUCTO=ID_ESTADO_BAJA WHERE ID_TIPO_PRODUCTO=ID_CATEGORIA_FIJO;
                UPDATE TIPO_PRODUCTO SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE ID_TIPO_PRODUCTO=ID_CATEGORIA_FIJO;
            ELSE
                SIGNAL SQLSTATE '20062' SET MESSAGE_TEXT = "ESTADO DE TRANSACCION INDEFINIDO EN EL MÓDULO DE CATEGORIA";
                ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20115' SET MESSAGE_TEXT ="¡Usted no tiene privilegios!, COMUNÍQUESE CON EL DESARROLLADOR";
            ROLLBACK;
        END IF;
    COMMIT;
    END;




/*******************************************************************************/
                        /*función CRUD CATEGORIA PRODUCTO */
/*******************************************************************************/

/*FUNCION QUE DEVUELVE EL ID DEL ESTADO CUANDO ES DE "BAJA" EN LA TABLA ESTADO_TIPO_PRODUCTO*/
DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ESTADO_CATEGORIA_BAJA;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ESTADO_CATEGORIA_BAJA() RETURNS INT(03)
BEGIN
    DECLARE ID_ESTADO_BAJA INT;
    SET ID_ESTADO_BAJA=(SELECT ID_ESTADO_TIPO_PRODUCTO FROM ESTADO_TIPO_PRODUCTO WHERE DESCRIPCION="BAJA");

    IF(ID_ESTADO_BAJA!='') THEN
        RETURN ID_ESTADO_BAJA;
    ELSE
        SIGNAL SQLSTATE '20046' SET MESSAGE_TEXT = "ERROR AL BUSCAR EL ESTADO DE categiria, CONTACTE AL DBA";
    END IF;
END// DELIMITER;


SELECT * FROM ESTADO_PRODUCTO;





/*******************************************************************************/
                        /*TRIGGERS CRUD CATEGORIA PRODUCTO */
/*******************************************************************************/

DROP TRIGGER IF EXISTS AI_CATEGORIA; 
/*Trigger para registar la creación de un nuevo TIPO DE PRODUCTO*/
CREATE TRIGGER AI_CATEGORIA
    AFTER INSERT ON TIPO_PRODUCTO
    FOR EACH ROW
    BEGIN
      INSERT INTO BITACORA_TIPO_PRODUCTO(ID_TIPO_PRODUCTO,NIP_USUARIO_RESP,ID_TIPO_MOVIMIENTO,FECHA_MOVIMIENTO)
      VALUES(new.ID_TIPO_PRODUCTO,new.NIP_ULT_USR_MODIFICADOR,1,NOW());
  	END
COMMIT;


/*Trigger para registar la actualización de los datos de un proveedor*/
DROP TRIGGER IF EXISTS AU_CATEGORIA; 

CREATE DEFINER=`root`@`localhost` TRIGGER AU_CATEGORIA
AFTER UPDATE ON TIPO_PRODUCTO
FOR EACH ROW
BEGIN 
    /*Variable para capturar LA DESCRIPCION del estado de la categoría
    que se está modificando*/    
    DECLARE ESTADO_TIPO_PRODUCTO VARCHAR(100);
    /*en las comparaciones adicionales new.variable<>old.variable
    estamos registrando únicamente los movimientos cuando las variables
    son alteradas de valor, es decir, cambian.*/
    /*SI VARIA LA DESCRIPCION DE LA CATEGORIA*/
    IF(NEW.DESCRIPCION IS NOT NULL AND NEW.DESCRIPCION<>OLD.DESCRIPCION) THEN
            INSERT INTO BITACORA_TIPO_PRODUCTO(ID_TIPO_PRODUCTO,VALOR_ANTIGUO,VALOR_NUEVO,
            NIP_USUARIO_RESP,ID_CAMPO_AFECTADO,ID_TIPO_MOVIMIENTO,FECHA_MOVIMIENTO)
            VALUES(NEW.ID_TIPO_PRODUCTO,OLD.DESCRIPCION,NEW.DESCRIPCION,NEW.NIP_ULT_USR_MODIFICADOR,2,2,NOW());
    ELSEIF(NEW.ID_ESTADO_TIPO_PRODUCTO IS NOT NULL  AND NEW.ID_ESTADO_TIPO_PRODUCTO<>OLD.ID_ESTADO_TIPO_PRODUCTO) THEN
        /*veo a que valor coincide el nuevo estado del usuario*/
        SELECT DESCRIPCION INTO ESTADO_TIPO_PRODUCTO FROM ESTADO_TIPO_PRODUCTO 
        WHERE ID_ESTADO_TIPO_PRODUCTO=NEW.ID_ESTADO_TIPO_PRODUCTO LIMIT 1;
        /*si la categoría pasa de activa a inactiva o viceversa*/
        IF (ESTADO_TIPO_PRODUCTO='ACTIVO' OR ESTADO_TIPO_PRODUCTO='INACTIVO') THEN
            INSERT INTO BITACORA_TIPO_PRODUCTO(ID_TIPO_PRODUCTO,VALOR_ANTIGUO,VALOR_NUEVO,
            NIP_USUARIO_RESP,ID_CAMPO_AFECTADO,ID_TIPO_MOVIMIENTO,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_TIPO_PRODUCTO,OLD.ID_ESTADO_TIPO_PRODUCTO,NEW.ID_ESTADO_TIPO_PRODUCTO,
            NEW.NIP_ULT_USR_MODIFICADOR,3,2,NOW());
            /*SI EL USUARIO PASARÁ A BAJA, EN LA BITÁCORA REGISTRAMOS QUE EL TIPO DE 
        MOVIMIENTO = 3 QUE PERTENECE A "BAJA" DEL SISTEMA (NO ES BORRADO)*/ 
         ELSEIF(ESTADO_TIPO_PRODUCTO='BAJA') THEN
            INSERT INTO BITACORA_TIPO_PRODUCTO(ID_TIPO_PRODUCTO,VALOR_ANTIGUO,VALOR_NUEVO,
            NIP_USUARIO_RESP,ID_CAMPO_AFECTADO,ID_TIPO_MOVIMIENTO,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_TIPO_PRODUCTO,OLD.ID_ESTADO_TIPO_PRODUCTO,NEW.ID_ESTADO_TIPO_PRODUCTO,
            NEW.NIP_ULT_USR_MODIFICADOR,3,3,NOW());
        END IF;
    ELSEIF(NEW.NIP_ULT_USR_MODIFICADOR IS NOT NULL AND NEW.NIP_ULT_USR_MODIFICADOR<>OLD.NIP_ULT_USR_MODIFICADOR ) THEN
            /*INDEPENDIENTEMENTE SI ES EL MISMO USUARIO QUE CREO O MODIFICO POR ÚLITMA VEZ
            EL REGISTRO, DEBEMOS GRABAR TODAS LAS TRANSACCIONES QUE REALICE*/
            INSERT INTO BITACORA_TIPO_PRODUCTO(ID_TIPO_PRODUCTO,VALOR_ANTIGUO,VALOR_NUEVO,
            NIP_USUARIO_RESP,ID_CAMPO_AFECTADO,ID_TIPO_MOVIMIENTO,FECHA_MOVIMIENTO)  
            VALUES(NEW.ID_TIPO_PRODUCTO,OLD.ID_ESTADO_TIPO_PRODUCTO,NEW.ID_ESTADO_TIPO_PRODUCTO,
            NEW.NIP_ULT_USR_MODIFICADOR,4,2,NOW());
    END IF;
END;



/*Regla 5:
    Será posible la inserción, actualización y baja de productos
    dentro del sistema.
    La actualización del estado de producto (de activo a inactivo) únicamente se podrá dar
    en el caso que la existencia =0 e igualmente su borrado. (baja del sistema)
*/
/*******************************************************************************/
                        /*     CRUD  DE PRODUCTOS     */
/*******************************************************************************/


/*******************************************************************************/
                        /*    VISTA CRUD PRODUCTO */
/*******************************************************************************/

DROP VIEW IF EXISTS VISTA_CRUD_PRODUCTO;
CREATE VIEW VISTA_CRUD_PRODUCTO
(SKU,DESCRIPCION_PRODUCTO,PRECIO_COSTO,PRIORIDAD,TIPO_PRODUCTO,ESTADO_PRODUCTO,STOCK_MINIMO,STOCK_MAXIMO,EXISTENCIA)
AS
(SELECT PRO.SKU,PRO.DESCRIPCION,PRO.PRECIO_COSTO,IFNULL(PRO.ID_PRIORIDAD,'AL INGRESAR EXISTENCIA'),
TP.DESCRIPCION,EP.DESCRIPCION,PRO.STOCK_MINIMO,PRO.STOCK_MAXIMO,
IFNULL(PRO.EXISTENCIA,'REGISTRE APROVISIONAMIENTO') FROM PRODUCTO PRO
INNER JOIN TIPO_PRODUCTO TP
ON TP.ID_TIPO_PRODUCTO = PRO.ID_TIPO_PRODUCTO
LEFT JOIN TIPO_PRIORIDAD TPRIORIDAD
ON PRO.ID_PRIORIDAD=TPRIORIDAD.ID_PRIORIDAD
 INNER JOIN ESTADO_PRODUCTO EP
 ON EP.ID_ESTADO_PRODUCTO=PRO.ID_ESTADO_PRODUCTO AND EP.DESCRIPCION<>'BAJA');


SELECT '2020-11-01' - INTERVAL DAYOFMONTH('2020-11-01') - 1 DAY AS PRIMER_DIA_MES;

SELECT LAST_DAY('2020-11-18') AS ULTIMO_DIA_MES;


/*******************************************************************************/
                        /*    PROCEDIMIENTOS ALMACENADOS CRUD PRODUCTO */
/*******************************************************************************/


/*CREAR UN PRODUCTO*/
DROP PROCEDURE IF EXISTS PA_CREAR_PRODUCTO_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_CREAR_PRODUCTO_SISTEMA`(IN SKU_NUEVO INT, 
IN DESCRIPCION_NUEVO VARCHAR(200),IN PRECIO_COSTO_NUEVO DECIMAL(10,2),
IN DESCRIPCION_TIPO_PRODUCTO_NUEVO VARCHAR(100),IN STOCK_MINIMO_NUEVO INT(10),IN STOCK_MAXIMO_NUEVO INT(10),IN NIP_USR_RESP INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20094' SET MESSAGE_TEXT = "COMUNIQUESE CON EL DBA";
    END;
    START TRANSACTION;

         /*EVALUAMOS QUE EL USUARIO DEL SISTEMA QUE QUIERE CREAR EL USUARIO EXISTA
        EN LA BD*/
        SET @EXISTE_USUARIO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO(NIP_USR_RESP));
        /*SI EXISTE EL USUARIO QUE QUIERE CREAR EL PRODUCTO*/ 
        IF(@EXISTE_USUARIO>0) THEN
            /*SI LA CATEGORÍA DE PRODUCTO EXISTE*/
            SET @EXISTE_CATEGORIA=(SELECT FUNCT_EXISTE_CATEGORIA_PRODUCTO(DESCRIPCION_TIPO_PRODUCTO_NUEVO));
                IF(@EXISTE_CATEGORIA>0) THEN
                    /*SI EL PRODUCTO AÚN NO EXISTE EN LA BASE DE DATOS NOS DEVUELVE 0 Y SI EXISTE, 1*/
                    SET @EXISTE_SKU=(SELECT FUNCT_EXISTE_SKU(SKU_NUEVO));
                    IF(@EXISTE_SKU=0) THEN
                        /*evaluamos que el stock mínimo no sea mayor al stock máximo*/
                        IF(STOCK_MINIMO_NUEVO<STOCK_MAXIMO_NUEVO) THEN
                            INSERT INTO PRODUCTO (SKU,DESCRIPCION,PRECIO_COSTO,ID_TIPO_PRODUCTO,ID_ESTADO_PRODUCTO,STOCK_MINIMO,STOCK_MAXIMO,FECHA_CREACION,NIP_ULT_USR_MODIFICADOR)
                            VALUES(SKU_NUEVO,DESCRIPCION_NUEVO,PRECIO_COSTO_NUEVO,@EXISTE_CATEGORIA,1,STOCK_MINIMO_NUEVO,STOCK_MAXIMO_NUEVO,NOW(),NIP_USR_RESP);
                        ELSE
                            SIGNAL SQLSTATE '20097' SET MESSAGE_TEXT="EL STOCK MÍNIMO NO PUEDE SER MAYOR O IGUAL AL MÁXIMO";
                            ROLLBACK;
                        END IF;
                    ELSE
                        SIGNAL SQLSTATE '20096' SET MESSAGE_TEXT="EL PRODUCTO YA EXISTE EN EL SISTEMA";
                        ROLLBACK;
                    END IF;
                ELSE
                    SIGNAL SQLSTATE '20097' SET MESSAGE_TEXT="LA CATEGORIA DE PRODUCTO NO EXISTE, CONTACTE AL DESARROLLADOR";
                    ROLLBACK;
                END IF;
        ELSE
            SIGNAL SQLSTATE '20095' SET MESSAGE_TEXT = "CONTACTE AL DESARROLLADOR";
        END IF;
        COMMIT;
END;






/*ACTUALIZACION DE PRODUCTO*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZAR_PRODUCTO;
DELIMITER // 
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZAR_PRODUCTO`(IN SKU_PRODUCTO_ACTUAL INT, IN DESCRIPCION_CAMBIAR VARCHAR(200),
IN PRECIO_COSTO_CAMBIAR DECIMAL(10,2),IN DESCRIPCION_TIPO_PRODUCTO_CAMBIAR VARCHAR(100),IN STOCK_MINIMO_CAMBIAR INT,
IN STOCK_MAXIMO_CAMBIAR INT, IN NIP_USR_RESPONSABLE INT)

BEGIN
    /*variables para capturar los datos recuperados de la bd de la tabla PRODUCTO
    a través de un cursor*/
    DECLARE C_SKU_PRODUCTO INT(03);
    DECLARE C_DESCRIPCION VARCHAR(200);
    DECLARE C_PRECIO_COSTO DECIMAL(10,2);
    DECLARE C_ID_TIPO_PRODUCTO INT(03);
    DECLARE C_STOCK_MINIMO INT(10);
    DECLARE C_STOCK_MAXIMO INT(10);
    DECLARE C_NIP_ULT_USR_MODIFICADOR INT(10);

    /*variable para cerrar el loop*/
    DECLARE FIN_LOOP INTEGER DEFAULT 0;

    /*creo un cursor para recuperar los datos del PRODUCTO a actualizar (datos)*/
    DECLARE CURSOR_SELECCION_PRODUCTO CURSOR FOR
    SELECT SKU,DESCRIPCION,PRECIO_COSTO,ID_TIPO_PRODUCTO,STOCK_MINIMO,STOCK_MAXIMO,NIP_ULT_USR_MODIFICADOR
    FROM PRODUCTO WHERE SKU=SKU_PRODUCTO_ACTUAL;

    /*VARIABLE PARA CONTROLAR EL FINAL DE RECORRIDO DEL CURSOR*/
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET FIN_LOOP=1; 
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20106' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de PRODUCTOS";
    END;

    START TRANSACTION;
        /*EVALUAMOS QUE EL USUARIO DEL SISTEMA QUE QUIERE CREAR EL USUARIO EXISTA
        EN LA BD Y ESTÉ ACTIVO*/
        SET @EXISTE_USUARIO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO(NIP_USR_RESPONSABLE));
        /*SI EXISTE EL USUARIO QUE QUIERE CREAR EL PRODUCTO*/ 
        IF(@EXISTE_USUARIO=1) THEN
            /*abrimos el cursor*/
            OPEN CURSOR_SELECCION_PRODUCTO;
                CICLO: LOOP
                    /*AGREGO EN EL CURSOR LAS VARIABLES PARA ALMACENAR LOS DATOS RECUPERADOS
                    DEL PRODUCTO, ALBERGADOS EN LA BASE DE DATOS*/
                    FETCH CURSOR_SELECCION_PRODUCTO INTO C_SKU_PRODUCTO,C_DESCRIPCION,
                    C_PRECIO_COSTO,C_ID_TIPO_PRODUCTO,C_STOCK_MINIMO,C_STOCK_MAXIMO,C_NIP_ULT_USR_MODIFICADOR;
                        /*comprobamos si llegamos al final de los registros obtenidos del cursor*/
                        IF FIN_LOOP = 1 THEN
                            LEAVE CICLO;
                        END IF;
                            /*SI LA CATEGORÍA DE PRODUCTO EXISTE*/
                            SET @EXISTE_CATEGORIA=(SELECT FUNCT_EXISTE_CATEGORIA_PRODUCTO(DESCRIPCION_TIPO_PRODUCTO_CAMBIAR));
                            IF(@EXISTE_CATEGORIA>0) THEN
                                /*SI EL PRODUCTO AÚN NO EXISTE EN LA BASE DE DATOS NOS DEVUELVE 0 Y SI EXISTE, 1*/
                                SET @EXISTE_SKU=(SELECT FUNCT_EXISTE_SKU(SKU_PRODUCTO_ACTUAL));
                                IF(@EXISTE_SKU=1) THEN
                                    /*evaluamos que el stock mínimo no sea mayor al stock máximo*/
                                    IF(STOCK_MINIMO_CAMBIAR<STOCK_MAXIMO_CAMBIAR) THEN
                                        /*UPDATES*/
                                        /*HARÉ VALIDACIÓN DE CAMPO POR CAMPO PARA COMPARAR SI VARIA EL VALOR ANTIGUO
                                        DE CADA UNO CON EL NUEVO ENVIADO POR EL USUARIO QUE QUIERE ACTUALIZAR LOS DATOS,
                                        PUES SI HAGO UN SOLO UPDATE, EL TRIGGER SE DISPARARÍA A NIVEL DE SENTENCIA, POR ENDE,
                                        NO SE REGISTRARÍAN LOS VALORES ANTIGUOS DEL PRODUCTO*/

                                        /*SI CAMBIAN EN NOMBRE DEL PRODUCTO*/
                                        IF(DESCRIPCION_CAMBIAR<>C_DESCRIPCION) THEN
                                            UPDATE PRODUCTO SET DESCRIPCION=DESCRIPCION_CAMBIAR WHERE SKU=SKU_PRODUCTO_ACTUAL;
                                        END IF;
                                        /*SI CAMBIAN EL PRECIO COSTO DEL PRODUCTO*/
                                        IF(PRECIO_COSTO_CAMBIAR<>C_PRECIO_COSTO) THEN
                                            UPDATE PRODUCTO SET PRECIO_COSTO=PRECIO_COSTO_CAMBIAR WHERE SKU=SKU_PRODUCTO_ACTUAL;
                                        END IF;
                                        /*SI CAMBIAN EL TIPO DE PRODUCTO (CATEGORIA)*/
                                        IF(@EXISTE_CATEGORIA<>C_ID_TIPO_PRODUCTO) THEN
                                            UPDATE PRODUCTO SET ID_TIPO_PRODUCTO=@EXISTE_CATEGORIA WHERE SKU=SKU_PRODUCTO_ACTUAL;
                                        END IF;
                                        /*SI CAMBIAN CORRECTAMENTE EL STOCK MÍNIMO*/
                                        IF(STOCK_MINIMO_CAMBIAR<>C_STOCK_MINIMO) THEN
                                            UPDATE PRODUCTO SET STOCK_MINIMO =STOCK_MINIMO_CAMBIAR WHERE SKU=SKU_PRODUCTO_ACTUAL;
                                        END IF;
                                        /*SI CAMBIAN CORRECTAMENTE EL STOCK MÁXIMO*/
                                        IF(STOCK_MAXIMO_CAMBIAR<>C_STOCK_MAXIMO) THEN
                                            UPDATE PRODUCTO SET STOCK_MAXIMO =STOCK_MAXIMO_CAMBIAR WHERE SKU=SKU_PRODUCTO_ACTUAL;
                                        END IF;
                                        /*Registramos el usuario modificador de los productos,
                                        aunque sea el úlitmo que tambien haya editado, hay que hacer que se dispare
                                        el trigger con este id a fin de saber quien modificó los demás campos*/
                                        UPDATE PRODUCTO SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESPONSABLE WHERE SKU=SKU_PRODUCTO_ACTUAL;

                                    ELSE
                                        SIGNAL SQLSTATE '20102' SET MESSAGE_TEXT="EL STOCK MÍNIMO NO PUEDE SER MAYOR O IGUAL AL MÁXIMO";
                                        ROLLBACK;
                                    END IF;
                                ELSE
                                    SIGNAL SQLSTATE '20103' SET MESSAGE_TEXT="NO EXISTE UN PRODUCTO CON EL SKU PROPORCIONADO";
                                    ROLLBACK;
                                END IF;
                            ELSE
                                SIGNAL SQLSTATE '20104' SET MESSAGE_TEXT="LA CATEGORIA DE PRODUCTO NO EXISTE, CONTACTE AL DBA";
                                ROLLBACK;
                            END IF;    
                END LOOP CICLO;
            CLOSE CURSOR_SELECCION_PRODUCTO;
        /*CONFIRMAMOS TODOS LOS UPDATES DE LOS DATOS DEL PRODUCTO*/    
        COMMIT;  
        ELSE
            SIGNAL SQLSTATE '20105' SET MESSAGE_TEXT = "CONTACTE AL DBA";
            ROLLBACK;
        END IF;
END;


/*ACTUALIZAR EL ESTADO DE UN PRODUCTO*/
/*PROCEDIMIENTO ALMACENADO PARA LA ACTUALIZACIÓN DE ESTADO
 DE ALGÚN PRODUCTO*/
 /*Procedimiento Almacenado para la actualización de estado de un producto*/
/*Recibe como parámetros el id de la categoria a modificar el estado, el estado nuevo (activo, inactivo)
y el NIP del usuario logueado que hace la modificación del estado*/
DROP PROCEDURE  IF EXISTS PA_ACTUALIZACION_ESTADO_PRODUCTO;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZACION_ESTADO_PRODUCTO`(IN SKU_FIJO INT, IN ESTADO_CATEGORIA INT,IN NIP_USR_RESP INT)
BEGIN

    /*VARIABLE PARA CAPTURAR EL RESULTADO DE EVALUACIÓN DE ESTADO Y VERIFICACION DE USUARIO EN EL SISTEMA*/
    DECLARE EXISTE_USUARIO_ACTIVO INT;
    /*VARIABLE PARA DEFINIR SI UN PRODUCTO TIENE EXISTENCIA O ACABA DE SER CREADO*/
    DECLARE TIENE_EXISTENCIA INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20034' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de estado CATEGORIA DE PRODUCTOS";
    END;

    START TRANSACTION;
        /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0*/
        SET EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(EXISTE_USUARIO_ACTIVO=1) THEN
            /*VALIDO QUE EL PRODUCTO NO TENGA EXISTENCIA AÚN O QUE NO SEA UN PRODUCTO
            RECIÉN CREADO DEL CUAL NO SE HA GUARDADO LA EXISTENCIA*/
            SET TIENE_EXISTENCIA=(SELECT FUNCT_PRODUCTO_TIENE_EXISTENCIA(SKU_FIJO));
            IF(TIENE_EXISTENCIA=0) THEN
                UPDATE PRODUCTO SET ID_ESTADO_PRODUCTO=ESTADO_CATEGORIA WHERE SKU=SKU_FIJO;
                UPDATE PRODUCTO SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE SKU=SKU_FIJO;
            ELSE
                SIGNAL SQLSTATE '20125' SET MESSAGE_TEXT="EL PRODUCTO AÚN TIENE STOCK O NO LE HA INGRESADO EXISTENCIA, OPERACIÓN CANCELADA";
                ROLLBACK;
            END IF;
        ELSE
        SIGNAL SQLSTATE '20035' SET MESSAGE_TEXT = "EL USUARIO ESTÁ INACTIVO O NO EXISTE EN EL SISTEMA";
        END IF;
    COMMIT;
    END;

COMMIT;
    

DROP PROCEDURE  IF EXISTS PA_BAJA_PRODUCTO_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_BAJA_PRODUCTO_SISTEMA`(IN SKU_FIJO INT,IN NIP_USR_RESP INT)
BEGIN
    /*Variable para recuperar el id del estado "baja" De la tabla tipo estado categoria*/
    DECLARE ID_ESTADO_BAJA INT;
    /*VERIFICAR QUE EL USUARIO DEL SISTEMA ESTÁ ACTIVO Y EXISTE EN LA BD*/
    DECLARE EXISTE_USUARIO_ACTIVO INT;
    /*VARIABLE PARA VERIFICAR QUE EL PRODUCTO A DAR DE BAJA, PREVIAMENENTE NO TENGA EXISTENCIAS (ESTÉ EN 0)
    Y QUE NO SEA NULL (ESTO NOS INDICA QUE HA SIDO CREADO, PERO NO LE HAN INGRESADO EXISTENCIA)*/
    DECLARE TIENE_EXISTENCIA INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20132' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de DAR DE BAJA A LA CATEGORIA DE SISTEMA";
    END;
    START TRANSACTION;
         /*valido si existe el usuario responsable de la gestión,
        SI existe y está activo, devuelve 1, sino 0*/
        SET EXISTE_USUARIO_ACTIVO=(SELECT FUNCT_EXISTE_USR_SISTEMA_ACTIVO (NIP_USR_RESP));
        IF(EXISTE_USUARIO_ACTIVO=1) THEN
            /*VALIDO QUE EL PRODUCTO NO TENGA EXISTENCIA AÚN O QUE NO SEA UN PRODUCTO
            RECIÉN CREADO DEL CUAL NO SE HA GUARDADO LA EXISTENCIA*/
            SET TIENE_EXISTENCIA=(SELECT FUNCT_PRODUCTO_TIENE_EXISTENCIA(SKU_FIJO));
            IF(TIENE_EXISTENCIA=0) THEN
                /*BUSCO EL ID DE ESTADO DE BAJA = BORRADO (SOLO SE OCULTA DE LA VISTA DEL USUARIO, NO SE BORRA DE LA BD)*/
                SET ID_ESTADO_BAJA =(SELECT FUNCT_DEVOLVER_ESTADO_PRODUCTO_BAJA());
                /*SI EL ESTADO DE PRODUCTO EFECTIVAMENTE EXISTE EN LA BASE DE DATOS*/
                IF(ID_ESTADO_BAJA>0) THEN
                    /*AL VALIDAR TODO LO ANTERIORMENTE DESCRITO, REALIZO LA ACTUALIZACIÓN  DE ESTADO ACTIVO / INACTIVO A BAJA*/
                    UPDATE PRODUCTO SET ID_ESTADO_PRODUCTO=ID_ESTADO_BAJA WHERE SKU=SKU_FIJO;
                    UPDATE PRODUCTO SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE SKU=SKU_FIJO;
                ELSE
                    SIGNAL SQLSTATE '20126' SET MESSAGE_TEXT="CONTACTE AL DESARROLLADOR";
                END IF;

            ELSE
                SIGNAL SQLSTATE '20125' SET MESSAGE_TEXT="EL PRODUCTO AÚN TIENE STOCK O NO LE HA INGRESADO EXISTENCIA, OPERACIÓN CANCELADA";
                ROLLBACK;
            END IF;
        ELSE
        SIGNAL SQLSTATE '20124' SET MESSAGE_TEXT = " USUARIO  INACTIVO O NO EXISTE EN EL SISTEMA";
        END IF;
    COMMIT;
    END;








/*******************************************************************************/
                        /*    FUNCIONES CRUD PRODUCTO */
/*******************************************************************************/

/*FUNCION PARA INDICAR AL USUARIO QUE EL PRODUCTO CON EL SKU
INGRESADO YA EXISTE EN EL SISTEMA"*/
DROP FUNCTION IF EXISTS FUNCT_EXISTE_SKU;
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_SKU(SKU_NUEVO INT) RETURNS INT
BEGIN
    SET @SKU1=(SELECT COUNT(*) FROM PRODUCTO WHERE SKU=SKU_NUEVO);

    IF(@SKU1>0) THEN
        RETURN @SKU1;
    ELSE
        RETURN @SKU1;
    END IF;
END// DELIMITER;



/*FUNCIÓN PARA RETORNAR EL ID DE LA CATEGORIA DE PRODUCTOS*/
DROP FUNCTION IF EXISTS FUNCT_EXISTE_CATEGORIA_PRODUCTO; 
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_CATEGORIA_PRODUCTO(DESCRIPCION_CATEGORIA VARCHAR(100)) RETURNS INT
BEGIN 
    /*AGREGO LIMIT PARA EVITAR EXCEPCIÓN DE TOO MANY ROWS*/
    SET @RESULTADO=(SELECT ID_TIPO_PRODUCTO FROM TIPO_PRODUCTO WHERE DESCRIPCION=DESCRIPCION_CATEGORIA
    AND ID_ESTADO_TIPO_PRODUCTO<>3 LIMIT 1);
    IF(@RESULTADO>0) THEN
        RETURN @RESULTADO;
    ELSE
        RETURN 0;
    END IF;
END// DELIMITER;


/*función para comprobar la existencia de un producto, si tiene, nos devuelve 1 y sino 0*/

DROP FUNCTION IF EXISTS FUNCT_PRODUCTO_TIENE_EXISTENCIA;
DELIMITER // 
CREATE FUNCTION FUNCT_PRODUCTO_TIENE_EXISTENCIA(SKU_PRODUCTO INT) RETURNS INT
BEGIN 

    DECLARE EXISTENCIA_ACTUAL_PRODUCTO INT;
    SET EXISTENCIA_ACTUAL_PRODUCTO=(SELECT EXISTENCIA FROM PRODUCTO WHERE SKU=SKU_PRODUCTO);
    /*SI LA EXISTENCIA DEL PRODUCTO ES NULA = ESTO NOS INDICA QUE EL PRODUCTO ACABA DE SER CREADO
    O SI EL PRODUCTO TIENE UNA EXISTENCIA MAYOR A 0*/
    IF(ISNULL(EXISTENCIA_ACTUAL_PRODUCTO) OR EXISTENCIA_ACTUAL_PRODUCTO>0 AND EXISTENCIA_ACTUAL_PRODUCTO>-1)THEN
        RETURN 1;
    ELSE 
        RETURN 0;
    END IF;
END // DELIMITER;

/*verificar si el estado de producto 'BAJA' existe en la tabla de estado de producto*/
DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ESTADO_PRODUCTO_BAJA;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ESTADO_PRODUCTO_BAJA() RETURNS INT(03)
BEGIN
    DECLARE ID_ESTADO_BAJA INT;
    SET ID_ESTADO_BAJA=(SELECT ID_ESTADO_PRODUCTO FROM ESTADO_PRODUCTO WHERE DESCRIPCION="BAJA" LIMIT 1);

    IF(ID_ESTADO_BAJA!='') THEN
        RETURN ID_ESTADO_BAJA;
    ELSE
        SIGNAL SQLSTATE '20133' SET MESSAGE_TEXT = "ERROR AL BUSCAR EL ESTADO DE PRODUCTO, CONTACTE AL DBA";
    END IF;
END// DELIMITER;





/*******************************************************************************/
                        /*    triggers CRUD PRODUCTO */
/*******************************************************************************/









/*Regla 6
    Será posible la inserción, actualización y baja de clientes
    dentro del sistema.
*/


/*******************************************************************************/
                        /*     CRUD  DE CLIENTES     */
/*******************************************************************************/

/*******************************************************************************/
                        /*   VISTA  CRUD  DE CLIENTES     */
/*******************************************************************************/

DROP VIEW IF EXISTS VISTA_CRUD_CLIENTE;
CREATE VIEW VISTA_CRUD_CLIENTE
(NIP, NOMBRES,APELLIDOS,CORREO_INSTITUCIONAL,SECCION,ESTADO_CLIENTE)
AS
(
    SELECT C.NIP,C.NOMBRES,C.APELLIDOS,C.CORREO_INSTITUCIONAL,S.DESCRIPCION AS SECCION_NOMBRE,EC.DESCRIPCION
    FROM CONSUMIDOR_FINAL C 
    INNER JOIN SECCION S 
    ON C.ID_SECCION=S.ID_SECCION
    INNER JOIN ESTADO_CLIENTE EC
    ON C.ID_ESTADO_CLIENTE=EC.ID_ESTADO_CLIENTE AND EC.DESCRIPCION<>'BAJA'
);

select * from seccion;


/*******************************************************************************/
                        /*    FUNCIONES CRUD CLIENTES */
/*******************************************************************************/


/*FUNCION PARA DEVOLVER EL ID DEL TIPO DE "ESTADO BAJA en la tabla de clientes"*/
DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ESTADO_CLI_BAJA;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ESTADO_CLI_BAJA() RETURNS INT(03)
BEGIN
    DECLARE ID_ESTADO_BAJA INT;
    SET ID_ESTADO_BAJA=(SELECT ID_ESTADO_CLIENTE FROM ESTADO_CLIENTE WHERE DESCRIPCION="BAJA" LIMIT 1);

    IF(ID_ESTADO_BAJA!='') THEN
        RETURN ID_ESTADO_BAJA;
    ELSE
        SIGNAL SQLSTATE '20087' SET MESSAGE_TEXT = "ERROR AL BUSCAR EL ESTADO DE CLIENTE, CONTACTE AL DBA";
    END IF;
END// DELIMITER;


DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ID_SECCION;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ID_SECCION(NOMBRE VARCHAR(200)) RETURNS INT(03)
BEGIN
    /*DECLARE ID_SECCION INT;*/
    SET @ID_SECCION=(SELECT ID_SECCION FROM SECCION WHERE DESCRIPCION=NOMBRE LIMIT 1);


        RETURN @ID_SECCION;
END// DELIMITER;



/*FUNCIÓN PARA DETERMINAR SI EL NIP DE UN CLIENTE YA ESTÁ REGISTRADO EN EL SISTEMA*/
DROP FUNCTION IF EXISTS FUNCT_EXISTE_CLIENTE_SISTEMA;
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_CLIENTE_SISTEMA(NIP_NUEVO INT) RETURNS INT
BEGIN
    SET @VERIFICAR_NIP=(SELECT COUNT(*) FROM CONSUMIDOR_FINAL WHERE NIP=NIP_NUEVO);

    IF(@VERIFICAR_NIP>0) THEN
        RETURN @VERIFICAR_NIP;
    ELSE
        RETURN @VERIFICAR_NIP;
    END IF;
END// DELIMITER;
SELECT * FROM CONSUMIDOR_FINAL;

/*******************************************************************************/
                        /*    PROCEDIMIENTOS ALMACENADOS CRUD CLIENTES */
/*******************************************************************************/
USE PRUEBA_BODEGA_TFM;

/*CREAR UN CLIENTE*/
DROP PROCEDURE IF EXISTS PA_CREAR_CLIENTE_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_CREAR_CLIENTE_SISTEMA`(IN NIP_NUEVO INT, IN NOMBRES_CLI_NUEVO VARCHAR(100),IN APELLIDOS_CLI_NUEVO VARCHAR(100),IN CORREO_INSTITUCIONAL_CLI_NUEVO VARCHAR(100),IN NOMBRE_SECCION VARCHAR(200),IN NIP_ULT_USR_MOD INT)
BEGIN
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20092' SET MESSAGE_TEXT = "COMUNIQUESE CON EL DBA";
    END;
  
    START TRANSACTION;

        /*VALIDAMOS QUE EL CLIENTE NO EXISTE PREVIAMENTE*/
        SET @EXISTE_CLIENTE=(SELECT FUNCT_EXISTE_CLIENTE_SISTEMA(NIP_NUEVO));
        IF(@EXISTE_CLIENTE=0) THEN
            /*EVALUAMOS QUE LA SECCION EXISTA ROL EXISTE EN LA BD*/
            SET @SECCION_CLIENTE=(SELECT FUNCT_DEVOLVER_ID_SECCION(NOMBRE_SECCION));
            /*EVALUAMOS QUE EL USUARIO DEL SISTEMA QUE QUIERE CREAR EL USUARIO EXISTA
            EN LA BD ROL EXISTE EN LA BD*/
            SET @EXISTE_USUARIO=(SELECT COUNT(NIP) FROM USUARIO_SISTEMA 
            WHERE NIP=NIP_ULT_USR_MOD LIMIT 1);
            /*SI EL USUARIO QUE QUIERE CREAR AL CLIENTE EXISTE Y TAMBIEN LA SECCION QUE NOS ENVIAN*/ 
            IF(@EXISTE_USUARIO>0 AND @SECCION_CLIENTE>0) THEN
                INSERT INTO CONSUMIDOR_FINAL
                VALUES(NIP_NUEVO,NOMBRES_CLI_NUEVO,APELLIDOS_CLI_NUEVO,CORREO_INSTITUCIONAL_CLI_NUEVO,@SECCION_CLIENTE,1,NIP_ULT_USR_MOD);
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20090' SET MESSAGE_TEXT = "CONTACTE AL DBA";
                ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20098' SET MESSAGE_TEXT = "EL CLIENTE YA EXISTE EN EL SISTEMA, CONTACTE AL DESARROLLADOR";
            ROLLBACK;
        END IF;
END;





/*actualizacion de cliente*/
/*procedimiento almacenado para la actualización de datos de un cliente*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZAR_CLIENTE_SISTEMA; 
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZAR_CLIENTE_SISTEMA`(IN NIP_FIJO INT, IN NOMBRES_CLI_EDITAR VARCHAR(100),
IN APELLIDOS_CLI_EDITAR VARCHAR(100),IN CORREO_INSTITUCIONAL_CLI_EDITAR VARCHAR(100),
IN NOMBRE_SECCION_EDITAR VARCHAR(100),IN NIP_ULT_USR_MOD INT)
BEGIN
    /*variables para capturar los datos recuperados de la bd del cliente
    a través de un cursor*/
    DECLARE C_NIP INT(10);
    DECLARE C_NOMBRES VARCHAR(200);
    DECLARE C_APELLIDOS VARCHAR(200);
    DECLARE C_CORREO_INSTITUCIONAL VARCHAR(200);
    DECLARE C_ID_SECCION_CLIENTE INT(3);
    DECLARE C_NIP_ULT_USR_MODIFICADOR INT(10);
    /*variable para cerrar el loop*/
    DECLARE FIN_LOOP INTEGER DEFAULT 0;
    /*creo un cursor para recuperar los datos del cliente a actualizar (datos)*/
    DECLARE CURSOR_SELECCION_CLIENTE CURSOR FOR
    SELECT NIP,NOMBRES,APELLIDOS,CORREO_INSTITUCIONAL,ID_SECCION,NIP_ULT_USR_MODIFICADOR
    FROM CONSUMIDOR_FINAL WHERE NIP=NIP_FIJO;

    /*VARIABLE PARA CONTROLAR EL FINAL DE RECORRIDO DEL CURSOR*/
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET FIN_LOOP=1; 

    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20078' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION DE CLIENTE, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, SINO
    EN alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20079' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de cliente";
    END;

    START TRANSACTION;

        /*EVALUAMOS QUE LA SECCION EXISTA EN LA BD*/
        SET @NIDSEC_CLIENTE=(SELECT FUNCT_DEVOLVER_ID_SECCION(NOMBRE_SECCION_EDITAR));

        /*SI EL ID DE ROL DE SECCION SE ENCUENTRA DENTRO DE LOS PERMITIDOS*/ 
        IF(@NIDSEC_CLIENTE >0) THEN
            /*abrimos el cursor*/
            OPEN CURSOR_SELECCION_CLIENTE;
                CICLO: LOOP
                FETCH CURSOR_SELECCION_CLIENTE INTO C_NIP,C_NOMBRES, C_APELLIDOS,C_CORREO_INSTITUCIONAL,
                C_ID_SECCION_CLIENTE,C_NIP_ULT_USR_MODIFICADOR;
                    /*comprobamos si llegamos al final de los registros obtenidos del cursor*/
                    IF FIN_LOOP = 1 THEN
                        LEAVE CICLO;
                    END IF;
                    /*HARÉ VALIDACIÓN DE CAMPO POR CAMPO PARA IDENTIFICAR SI VARIA EL VALOR ANTIGUO
                    DE CADA UNO CON EL NUEVO ENVIADO POR EL USUARIO DESDE LA VISTA*/
                    /*SI SE ACTUALIZA EL NOMBRE DEL cliente*/
                    IF(NOMBRES_CLI_EDITAR<>C_NOMBRES) THEN
                        UPDATE CONSUMIDOR_FINAL SET NOMBRES=NOMBRES_CLI_EDITAR WHERE NIP=NIP_FIJO;
                    END IF;
                    /*SI LOS APELLIDOS O APELLIDO SON O ES ACTUALIZADO*/
                    IF(APELLIDOS_CLI_EDITAR<>C_APELLIDOS) THEN
                        UPDATE CONSUMIDOR_FINAL SET APELLIDOS=APELLIDOS_CLI_EDITAR WHERE NIP=NIP_FIJO;
                    END IF;

                    /*SI EL CORREO ELECTRÓNICO ES CAMBIADO*/
                    IF(C_CORREO_INSTITUCIONAL<> CORREO_INSTITUCIONAL_CLI_EDITAR) THEN    
                        UPDATE CONSUMIDOR_FINAL SET CORREO_INSTITUCIONAL=CORREO_INSTITUCIONAL_CLI_EDITAR WHERE NIP=NIP_FIJO;
                    END IF;
                    /*SI LA SECCION ES ALTERADA*/
                    IF(C_ID_SECCION_CLIENTE<>@NIDSEC_CLIENTE) THEN
                        UPDATE CONSUMIDOR_FINAL SET  ID_SECCION=@NIDSEC_CLIENTE WHERE NIP=NIP_FIJO;
                    END IF;
                    /*DEBEMOS REGISTAR QUE USUARIO CON PRIVILEGIOS DE ADMIN 
                    FUE EL ÚLITMO EN EDITAR A OTRO USUARIO*/
                    IF(C_NIP_ULT_USR_MODIFICADOR<>NIP_ULT_USR_MOD) THEN
                        UPDATE CONSUMIDOR_FINAL SET NIP_ULT_USR_MODIFICADOR=C_NIP_ULT_USR_MODIFICADOR
                        WHERE NIP=NIP_FIJO;
                    END IF;         
                END LOOP CICLO;
            CLOSE CURSOR_SELECCION_CLIENTE;
            COMMIT;
        ELSE
           SIGNAL SQLSTATE '20079' SET MESSAGE_TEXT = 'Comuníquese con el DBA';
        END IF;    
END;



/*Procedimiento Almacenado para la actualización de estado de un cliente*/
/*Recibe como parámetros el nip del cliente a modificar el estado, el estado nuevo (activo, inactivo)
y el NIP del cliente logueado que hace la modificación del estado*/
DROP PROCEDURE  IF EXISTS PA_ACTUALIZACION_ESTADO_CLI;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZACION_ESTADO_CLI`(IN NIP_FIJO INT, IN ESTADO_CLIENTE INT,IN NIP_USR_RESP INT)
BEGIN
    /*Variable para determinar si el cliente que quiere hacer el UPDATE 
    existe en la bd, y tiene rol de administrador*/
    DECLARE VALIDAR_ROL INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20080' SET MESSAGE_TEXT = "ERROR AL GRABAR LA ACTUALIZACION DE ESTADO, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20081' SET MESSAGE_TEXT = "error durante
         la ejecución del procedimiento de actualización de estado del CLIENTE";
    END;

    START TRANSACTION;
            UPDATE CONSUMIDOR_FINAL SET ID_ESTADO_CLIENTE=ESTADO_CLIENTE WHERE NIP=NIP_FIJO;
            UPDATE CONSUMIDOR_FINAL SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE NIP=NIP_FIJO;
    COMMIT;
    END;


/*PROCEDIMIENTO ALMACENAOD PARA DAR DE BAJA A UN cliente DEL SISTEMA*/
DROP PROCEDURE  IF EXISTS PA_BAJA_CLI_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_BAJA_CLI_SISTEMA`(IN NIP_FIJO INT,IN NIP_USR_RESP INT)
BEGIN
    /*Variable para recuperar el id del estado "baja"*/
    DECLARE ID_ESTADO_BAJA INT;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución de alguna consulta*/
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20084' SET MESSAGE_TEXT = "ERROR AL DAR DE BAJA EL cliente, COMUNIQUESE CON EL DBA";
    END;
    /*excepción para hacer rolLback si surge una excepción de sql durante la 
    ejecución del procedimiento almacenado (no en algún query como tal, sino
    en alguna fase de compilación del procedimiento almacenado)*/
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20085' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de BAJA DEL cliente";
    END;

    START TRANSACTION;

        /*RECUPERO EL ID DEL ESTADO "BAJA" CON UNA FUNCIÓN*/
        SET ID_ESTADO_BAJA=(SELECT FUNCT_DEVOLVER_ESTADO_CLI_BAJA());
        
            UPDATE CONSUMIDOR_FINAL SET ID_ESTADO_CLIENTE=ID_ESTADO_BAJA WHERE NIP=NIP_FIJO;
            UPDATE CONSUMIDOR_FINAL SET NIP_ULT_USR_MODIFICADOR=NIP_USR_RESP WHERE NIP=NIP_FIJO;
    COMMIT;
    END;



/*******************************************************************************/
                        /*TRIGGERS CRUD cliente */
/*******************************************************************************/
DROP TRIGGER IF EXISTS AI_CONSUMIDOR_FINAL_SISTEMA; 
/*Trigger para registar la creación de un nuevo usuario*/
CREATE DEFINER=`root`@`localhost` TRIGGER AI_CONSUMIDOR_FINAL_SISTEMA
    AFTER INSERT ON CONSUMIDOR_FINAL
    FOR EACH ROW
    BEGIN
      INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,ID_TIPO_MOVIMIENTO,
      NIP_USUARIO_RESP,FECHA_MOVIMIENTO)
      VALUES(new.NIP,1,new.NIP_ULT_USR_MODIFICADOR,NOW());
  	END


DROP TRIGGER IF EXISTS AU_CLIENTE_SISTEMA; 

CREATE DEFINER=`root`@`localhost` TRIGGER AU_CLIENTE_SISTEMA
AFTER UPDATE ON CONSUMIDOR_FINAL
FOR EACH ROW
BEGIN 
    /*Variable para capturar el nombre del estado del cliente
    que se está modificando*/    
    DECLARE ESTADO_CLI VARCHAR(100);
    /*en las comparaciones adicionales new.variable<>old.variable
    estamos registrando únicamente los movimientos cuando las variables
    son alteradas de valor, es decir, cambian.*/
    IF(NEW.NOMBRES IS NOT NULL AND NEW.NOMBRES<>OLD.NOMBRES) THEN
       
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)
            VALUES(NEW.NIP,2,OLD.NOMBRES,new.NOMBRES,2,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.APELLIDOS IS NOT NULL AND NEW.APELLIDOS<>OLD.APELLIDOS) THEN
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.APELLIDOS,NEW.APELLIDOS,3,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.CORREO_INSTITUCIONAL IS NOT NULL AND NEW.CORREO_INSTITUCIONAL<>OLD.CORREO_INSTITUCIONAL) THEN
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.CORREO_INSTITUCIONAL,NEW.CORREO_INSTITUCIONAL,
            4,NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.ID_SECCION IS NOT NULL AND NEW.ID_SECCION<>OLD.ID_SECCION) THEN
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.ID_SECCION,NEW.ID_SECCION,5,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    ELSEIF(NEW.ID_ESTADO_CLIENTE IS NOT NULL AND NEW.ID_ESTADO_CLIENTE<>OLD.ID_ESTADO_CLIENTE) THEN
        /*veo a que valor coincide el nuevo estado del usuario*/
        SELECT DESCRIPCION INTO ESTADO_CLI FROM ESTADO_CLIENTE 
        WHERE ID_ESTADO_CLIENTE=NEW.ID_ESTADO_CLIENTE LIMIT 1;
        /*si el usuario de estar inactivo pasa a activo*/
        IF (ESTADO_CLI='ACTIVO') THEN
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.ID_ESTADO_CLIENTE,NEW.ID_ESTADO_CLIENTE,6,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        /*SI EL USUARIO PASARÁ A INACTIVO, EN LA BITÁCORA REGISTRAMOS QUE EL TIPO DE 
        MOVIMIENTO = 3 QUE PERTENECE A "BAJA" DEL SISTEMA (NO ES BORRADO)*/    
        ELSEIF(ESTADO_CLI='INACTIVO') THEN
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,2,OLD.ID_ESTADO_CLIENTE,NEW.ID_ESTADO_CLIENTE,6,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
            /*si el usuario pasa de cualquiera de los dos estados
            anteriores a estado de baja (no será visible más por el usuario, solo por el dba)*/
            ELSEIF(ESTADO_CLI='BAJA') THEN
            INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
            ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
            NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
            VALUES(NEW.NIP,3,OLD.ID_ESTADO_CLIENTE,NEW.ID_ESTADO_CLIENTE,6,
            NEW.NIP_ULT_USR_MODIFICADOR,NOW());
        END IF;
    ELSEIF(NEW.NIP_ULT_USR_MODIFICADOR IS NOT NULL  AND NEW.NIP_ULT_USR_MODIFICADOR<>OLD.NIP_ULT_USR_MODIFICADOR) THEN
        INSERT INTO BITACORA_CLIENTE(NIP_CLIENTE,
        ID_TIPO_MOVIMIENTO,VALOR_ANTIGUO,VALOR_NUEVO,ID_CAMPO_AFECTADO,
        NIP_USUARIO_RESP,FECHA_MOVIMIENTO)  
        VALUES(NEW.NIP,2,OLD.NIP_ULT_USR_MODIFICADOR,NEW.NIP_ULT_USR_MODIFICADOR,8,
        NEW.NIP_ULT_USR_MODIFICADOR,NOW());
    END IF;
END




/*para verificar las secciones de Tanatología Forense Metropolitana*/

DROP VIEW IF EXISTS VISTA_SECCIONES;
CREATE VIEW VISTA_SECCIONES(DESCRIPCION)
AS
(SELECT DESCRIPCION FROM SECCION);



SELECT * FROM SECCION;