/*Creando tablas con solo llave primaria
EL sigueinte DDL estará orientado a una base de datos
mysql

Restricciones:
1.- El nombre de las llaves primarias irá compuesto por las iniciales
de cada palabra del nombre de la tabla
    * Se es primaria se agregará al inicio PK_
    *SI es foránea se agregará al inicio FK_
    *Si es una llave primaria compuesta, se agregará al inicio PKC_
    *Si es una llave foránea compuesta, se agregará al inici FKC_.

*/

CREATE TABLE ESTADO_PERITO
(
    ID_ESTADO_PERITO INT(3),
    NOMBRE VARCHAR(100)
);

/*AGREGANDO LLAVE PRIMARIA*/
ALTER TABLE ESTADO_PERITO ADD CONSTRAINT PK_EP PRIMARY KEY(ID_ESTADO_PERITO);
/*VOLVIENDO LA LLAVE PRIMARIA COMO CAMPO AUTOINCREMENTABLE*/
ALTER TABLE ESTADO_PERITO MODIFY COLUMN ID_ESTADO_PERITO INT(3) AUTO_INCREMENT;


/*tabla tipo_movimiento_usuario:*/
CREATE TABLE TIPO_MOVIMIENTO_USUARIO
(
    ID_TIPO_MOVIMIENTO INT(3),
    DESCRIPCION VARCHAR(100)
);
ALTER TABLE TIPO_MOVIMIENTO_USUARIO ADD CONSTRAINT PK_TMU PRIMARY KEY(ID_TIPO_MOVIMIENTO);
ALTER TABLE TIPO_MOVIMIENTO_USUARIO MODIFY COLUMN ID_TIPO_MOVIMIENTO INT(3) AUTO_INCREMENT;

CREATE TABLE CAMPO_AFECTADO_USUARIO
(
    ID_CAMPO INT(3),
    DESCRIPCION VARCHAR(100)
);
ALTER TABLE CAMPO_AFECTADO_USUARIO ADD CONSTRAINT PK_CAU PRIMARY KEY(ID_CAMPO);
ALTER TABLE CAMPO_AFECTADO_USUARIO MODIFY COLUMN ID_CAMPO INT(3) AUTO_INCREMENT;

CREATE TABLE ESTADO_GESTION
(
    ID_ESTADO_GESTION INT(3),
    NOMBRE VARCHAR(100)
);
ALTER TABLE ESTADO_GESTION ADD CONSTRAINT PK_EG PRIMARY KEY(ID_ESTADO_GESTION);
ALTER TABLE ESTADO_GESTION MODIFY COLUMN ID_ESTADO_GESTION INT(3) AUTO_INCREMENT;



CREATE TABLE TIPO_MOVIMIENTO_GESTION
(
    ID_TIPO_MOV INT(3),
    DESCRIPCION VARCHAR(100)
);
ALTER TABLE TIPO_MOVIMIENTO_GESTION ADD CONSTRAINT PK_TMG PRIMARY KEY(ID_TIPO_MOV);
ALTER TABLE TIPO_MOVIMIENTO_GESTION MODIFY COLUMN ID_TIPO_MOV INT(3) AUTO_INCREMENT;




CREATE TABLE CAMPO_AFECTADO_GESTION
(
    ID_CAMPO INT(3),
    NOMBRE VARCHAR(100)
);
ALTER TABLE CAMPO_AFECTADO_GESTION ADD CONSTRAINT PK_CAG PRIMARY KEY(ID_CAMPO);
ALTER TABLE CAMPO_AFECTADO_GESTION MODIFY COLUMN ID_CAMPO INT(3) AUTO_INCREMENT;




CREATE TABLE ESTADO_USUARIO
(
    ID_ESTADO_USR INT(3),
    NOMBRE VARCHAR(100)
);
ALTER TABLE ESTADO_USUARIO ADD CONSTRAINT PK_EU PRIMARY KEY(ID_ESTADO_USR);
ALTER TABLE ESTADO_USUARIO MODIFY COLUMN ID_ESTADO_USR INT(3) AUTO_INCREMENT;




CREATE TABLE ROL_USUARIO
(
    ID_ROL INT(3),
    DESCRIPCION VARCHAR(100)
);
ALTER TABLE ROL_USUARIO ADD CONSTRAINT PK_RU PRIMARY KEY(ID_ROL);
ALTER TABLE ROL_USUARIO MODIFY COLUMN ID_ROL INT(3) AUTO_INCREMENT;




CREATE TABLE SECCION
(
    ID_SECCION INT(3),
    NOMBRE VARCHAR(100),
    IDENTIFICADOR VARCHAR(10)
);
ALTER TABLE SECCION ADD CONSTRAINT PK_SEC PRIMARY KEY(ID_SECCION);
ALTER TABLE SECCION MODIFY COLUMN ID_SECCION INT(3) AUTO_INCREMENT;



CREATE TABLE CLASE_GESTION
(
    ID_CLASE_GESTION INT(3),
    NOMBRE VARCHAR(100)
);
ALTER TABLE CLASE_GESTION ADD CONSTRAINT PK_CS PRIMARY KEY(ID_CLASE_GESTION);
ALTER TABLE CLASE_GESTION MODIFY COLUMN ID_CLASE_GESTION INT(3) AUTO_INCREMENT;



/*en esta parte inician las tables con llaves primareas y foráneas*/


/* En el caso de la tabla PERITO, no se definirá como autoincrementable
el campo del id_perito, a causa que este número será el Número de Identificación
Personal (NIP) que posee cada colaborador el cual es designado por la unidad
de Recrusos humanos*/
CREATE TABLE PERITO
(
    ID_PERITO INT(6),
    NOMBRES VARCHAR(200),
    APELLIDOS VARCHAR(100),
    FECHA_CREACION TIMESTAMP,
    ID_ESTADO_PERITO INT(3)
);
ALTER TABLE PERITO ADD CONSTRAINT PK_PER PRIMARY KEY(ID_PERITO);
ALTER TABLE PERITO ADD CONSTRAINT FK_EP FOREIGN KEY(ID_ESTADO_PERITO) 
REFERENCES ESTADO_PERITO(ID_ESTADO_PERITO);


/*al igual que la tabla PERITO, la tabla USUARIO_SISTEMA
no poseerá en su id de llave primaria la característica de ser 
autoncrementable, pues el ID_USUARIO será ingresado conforme 
la asignación del NIP (Número de identifiacción Personal) 
al personal

El campo de contraseña (acceso) será encriptado posteriormente
cuando se inserten los datos a traves del algoritmo y funcion
que dota mysql SHA*
*/

CREATE TABLE USUARIO_SISTEMA
(
    ID_USUARIO INT(6),
    NOMBRES VARCHAR(200),
    APELLIDOS VARCHAR(100),
    ID_ESTADO_USR INT(3),
    ID_ROL INT(3),
    ACCESO VARCHAR(200),
    ULTIMO_LOGIN TIMESTAMP,
    ULTIMO_USUARIO_MODIFICADOR INT(6)
);
ALTER TABLE USUARIO_SISTEMA ADD CONSTRAINT PK_US PRIMARY KEY(ID_USUARIO);
ALTER TABLE USUARIO_SISTEMA ADD CONSTRAINT FK_IEU FOREIGN KEY(ID_ESTADO_USR)
REFERENCES ESTADO_USUARIO(ID_ESTADO_USR);
ALTER TABLE USUARIO_SISTEMA ADD CONSTRAINT FK_IDR FOREIGN KEY(ID_ROL)
REFERENCES ROL_USUARIO(ID_ROL);
ALTER TABLE USUARIO_SISTEMA ADD CONSTRAINT FK_URMOD FOREIGN KEY(ULTIMO_USUARIO_MODIFICADOR)
REFERENCES USUARIO_SISTEMA(ID_USUARIO);



CREATE TABLE TIPO_GESTION
(
    ID_TIPO_GESTION INT(3),
    NOMBRE VARCHAR(100),
    ID_CLASE_GESTION INT(3)
);
ALTER TABLE TIPO_GESTION ADD CONSTRAINT PK_TG PRIMARY KEY(ID_TIPO_GESTION);
ALTER TABLE TIPO_GESTION MODIFY COLUMN ID_TIPO_GESTION INT(3) AUTO_INCREMENT;
ALTER TABLE TIPO_GESTION ADD CONSTRAINT FK_ICG FOREIGN KEY(ID_CLASE_GESTION)
REFERENCES CLASE_GESTION(ID_CLASE_GESTION);



/*tabla principal sobre la cual será el ingreso de información
continua
    * EL campo correlativo será la llave primaria combinada
    con el campo anio, pero no sererá autoincrementable pues 
    como se detalló anteriormente, este numero se reinicia al 
    concluir cada año y es por esta causa que debe combinarse
    la restricción de llave primaria entre los 2 campos.
*/

CREATE TABLE GESTION
(
    CORRELATIVO INT(6),
    ANIO INT(4),
    ID_SECCION INT(3),
    ID_PERITO_RESPONSABLE INT(6),
    FECHA_INGRESO DATE,
    FECHA_TRANSCRIPCION DATE,
    FECHA_EGRESO DATE,
    ID_ESTADO_GESTION INT(3),
    ID_TIPO_GESTION INT(3),
    RESPONSABLE_GESTION INT(6),
    OBSERVACIONES VARCHAR(600),
    IMAGEN_ARCHIVO BLOB,
    ULTIMO_USUARIO_MODIFICADOR INT(6)
);
ALTER TABLE GESTION ADD CONSTRAINT PK_GEST PRIMARY KEY(CORRELATIVO,ANIO);
ALTER TABLE GESTION ADD CONSTRAINT FK_IDSEC FOREIGN KEY(ID_SECCION)
REFERENCES SECCION(ID_SECCION);
ALTER TABLE GESTION ADD CONSTRAINT FK_IDPRESP FOREIGN KEY(ID_PERITO_RESPONSABLE)
REFERENCES PERITO(ID_PERITO);
ALTER TABLE GESTION ADD CONSTRAINT FK_IEG FOREIGN KEY(ID_ESTADO_GESTION) REFERENCES
ESTADO_GESTION(ID_ESTADO_GESTION);
ALTER TABLE GESTION ADD CONSTRAINT FK_ITG FOREIGN KEY(ID_TIPO_GESTION) REFERENCES
TIPO_GESTION(ID_TIPO_GESTION);
ALTER TABLE GESTION ADD CONSTRAINT FK_USRRESP FOREIGN KEY(RESPONSABLE_GESTION)
REFERENCES USUARIO_SISTEMA(ID_USUARIO);
ALTER TABLE GESTION ADD CONSTRAINT FK_USRMODIFICADOR FOREIGN KEY(ULTIMO_USUARIO_MODIFICADOR)
REFERENCES USUARIO_SISTEMA(ID_USUARIO);



/*A Continuación la tabla que jugará el papel de 
bitácora en el sistema para garantizar el no repudio 
de operaciones por parte de los usuarios del sistema
*/
CREATE TABLE BITACORA_GESTION
(
    ID_TRANSACCION INT(6),
    CORRELATIVO_AFECTADO INT(6),
    ANIO INT(4),
    ID_TIPO_MOV INT(3),
    ID_CAMPO_AFECTADO INT(3),
    FECHA_MOVIMIENTO TIMESTAMP,
    ID_USUARIO_RESP INT(6),
    VALOR_ANTERIOR VARCHAR(100),
    VALOR_NUEVO VARCHAR(100)
);
ALTER TABLE BITACORA_GESTION ADD CONSTRAINT PK_BG PRIMARY KEY(ID_TRANSACCION);
ALTER TABLE BITACORA_GESTION ADD CONSTRAINT FK_GEST FOREIGN KEY(CORRELATIVO_AFECTADO,ANIO)
REFERENCES GESTION(CORRELATIVO,ANIO);
ALTER TABLE BITACORA_GESTION ADD CONSTRAINT FK_ITM FOREIGN KEY(ID_TIPO_MOV)
REFERENCES TIPO_MOVIMIENTO_GESTION(ID_TIPO_MOV);
ALTER TABLE BITACORA_GESTION ADD CONSTRAINT FK_ICA FOREIGN KEY(ID_CAMPO_AFECTADO)
REFERENCES CAMPO_AFECTADO_GESTION(ID_CAMPO);
ALTER TABLE BITACORA_GESTION ADD CONSTRAINT FK_IUR FOREIGN KEY(ID_USUARIO_RESP)
REFERENCES USUARIO_SISTEMA(ID_USUARIO);




/*Registrar los inserts, updates y deletes que harán en respecto a los datos de usuario*/

CREATE TABLE BITACORA_USUARIO
(
    ID_TRANSACCION INT(10),
    ID_USR_AFECTADO INT(6),
    ID_TIPO_MOVIMIENTO INT(3),
    ID_CAMPO_AFECTADO INT(3),
    FECHA_MOVIMIENTO TIMESTAMP,
    ID_USR_RESPONSABLE INT(6),
    VALOR_ANTERIOR VARCHAR(100),
    VALOR_NUEVO VARCHAR(100)
);
ALTER TABLE BITACORA_USUARIO ADD CONSTRAINT PK_BIUSR PRIMARY KEY (ID_TRANSACCION);
ALTER TABLE BITACORA_USUARIO MODIFY COLUMN ID_TRANSACCION INT(10) AUTO_INCREMENT;
ALTER TABLE BITACORA_USUARIO ADD CONSTRAINT FK_IDUSRAFEC FOREIGN KEY(ID_USR_AFECTADO)
REFERENCES USUARIO_SISTEMA(ID_USUARIO);
ALTER TABLE BITACORA_USUARIO ADD CONSTRAINT FK_IDCAAFEC FOREIGN KEY (ID_CAMPO_AFECTADO)
REFERENCES CAMPO_AFECTADO_USUARIO(ID_CAMPO);
ALTER TABLE BITACORA_USUARIO ADD CONSTRAINT FK_ITPMU FOREIGN KEY (ID_TIPO_MOVIMIENTO)
REFERENCES TIPO_MOVIMIENTO_USUARIO(ID_TIPO_MOVIMIENTO);





/****************************************************************************************************************/
                                        /*INSERCIÓN DE DATOS INICIALES*/
/****************************************************************************************************************/


/* CAMPO_AFECTADO_GESTION: IRÁN EL NOMBRE DE LOS CAMPOS CORRESPONDIENTES A LA TABLA GESTIÓN,
A FIN DE SABER CUANDO SE DISPAREN LOS TRIGGERS SOBRE ESA TABLA, QUE CAMPOS FUERON AFECTADOS POR 
ACCIONES DE UPDATE O DELETE (DELETE EN ESTE CASO SERÁ UN ESTADO DE BAJA, A FIN DE NO MOSTRAR
YA ESOS DATOS AL USUARIO PERO NO BORRARLOS DEL SISTEMA)*/
USE TFM;
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('CORRELATIVO');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('ANIO');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('ID_SECCION');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('ID_PERITO_RESPONSABLE');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('FECHA_INGRESO');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('FECHA_TRANSCRIPCION');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('FECHA_EGRESO');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('ID_ESTADO_GESTION');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('ID_TIPO_GESTION');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('RESPONSABLE_GESTION');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('OBSERVACIONES');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('IMAGEN_ARCHIVO');
INSERT INTO CAMPO_AFECTADO_GESTION(NOMBRE) VALUES('ULTIMO_USUARIO_RESPONSABLE');

/*TIPO_MOVIMIENTO_USUARIO: SE REGISTRÁ LOS MOVIMIENTOS QUE HAGA EL USUARIO FINAL SOBRE LA TABLA
USUARIO, ESTOS PUEDEN SER INSERCIÓN, ACTUALIZACIÓN O BORRADO=BAJA DEL SISTEMA AL HABLAR DE BAJA,
ME REFIERO A QUE LOS DATOS YA NO ESTARÁN VISIBLES PARA LOS USUARIOS FINALES, POR LO TANTO,
NO SERÁN ELIMINADOS DEL SISTEMA PARA PRESERVAR Y GARANTIZAR LA INTEGRIDAD DE LA INFORMACIÓN DESDE
ESTA PERSPECTIVA */
INSERT INTO TIPO_MOVIMIENTO_USUARIO(DESCRIPCION) VALUES('INSERCIÓN');
INSERT INTO TIPO_MOVIMIENTO_USUARIO(DESCRIPCION) VALUES('ACTUALIZACIÓN');
INSERT INTO TIPO_MOVIMIENTO_USUARIO(DESCRIPCION) VALUES('BAJA');




/*TIPO_MOVIMIENTO_GESTION: SE REGISTRÁ LOS MOVIMIENTOS QUE HAGA EL USUARIO FINAL SOBRE LA TABLA
GESTION, ESTOS PUEDEN SER INSERCIÓN, ACTUALIZACIÓN O BORRADO=BAJA DEL SISTEMA AL HABLAR DE BAJA,
ME REFIERO A QUE LOS DATOS YA NO ESTARÁN VISIBLES PARA LOS USUARIOS FINALES, POR LO TANTO,
NO SERÁN ELIMINADOS DEL SISTEMA PARA PRESERVAR Y GARANTIZAR LA INTEGRIDAD DE LA INFORMACIÓN DESDE
ESTA PERSPECTIVA */
INSERT INTO TIPO_MOVIMIENTO_GESTION(DESCRIPCION) VALUES('INSERCIÓN');
INSERT INTO TIPO_MOVIMIENTO_GESTION(DESCRIPCION) VALUES('ACTUALIZACIÓN');
INSERT INTO TIPO_MOVIMIENTO_GESTION(DESCRIPCION) VALUES('BAJA');


/*ESTADO_PERITO: UN PERITO PUEDE ESTAR ACTIVO E INACTIVO, PERO SE DEJARÁ LA OPCIÓN DE DARLE
DE "BAJA" DEL SISTEMA*/
INSERT INTO ESTADO_PERITO(NOMBRE) VALUES('ACTIVO');
INSERT INTO ESTADO_PERITO(NOMBRE) VALUES('INACTIVO');
INSERT INTO ESTADO_PERITO(NOMBRE) VALUES('BAJA');


/*ESTADO_GESTION: La gestión tendrá los siguientes estados:
1.-Recepción: La gestión fue creada.
2.-Transcripcion: La gestión se encuentra en proceso de digitalización de la información.
3.-Cotejo: La gestión fue asignada al perito y se encuentra en el análisis correspondiente.
4.-Firma: El expediente se encuentra impreso y está pendiente de firma por el perito a cargo
5.-Evacuación: Expediente fue entregado a la sección de seguimiento pericial (concluye el trabajo
por parte de Tanatología Forense Metropolitana);
*/

INSERT INTO ESTADO_GESTION(NOMBRE) VALUES('RECEPCION');
INSERT INTO ESTADO_GESTION(NOMBRE) VALUES('TRANSCRIPCION');
INSERT INTO ESTADO_GESTION(NOMBRE) VALUES('COTEJO');
INSERT INTO ESTADO_GESTION(NOMBRE) VALUES('FIRMA');
INSERT INTO ESTADO_GESTION(NOMBRE) VALUES('EVACUACION');


/*TIPO_MOVIMIENTO_USUARIO: DEFINE LAS ACCIONES QUE UN USUARIO REALIZA SOBRE LA TABLA USUARIO_SISTEMA
A REGISTAR EN LA BITÁCORA DE USUARIO, ESTAS SON: INSERCION, ACTUALIZACION O BAJA*/

INSERT INTO TIPO_MOVIMIENTO_GESTION(DESCRIPCION) VALUES('INSERCION');
INSERT INTO TIPO_MOVIMIENTO_GESTION(DESCRIPCION) VALUES('ACTUALIZACION');
INSERT INTO TIPO_MOVIMIENTO_GESTION(DESCRIPCION) VALUES('BAJA');

/*CAMPO_AFECTADO_USUARIO: CONTIENE EL NOMBRE DE LOS CAMPOS REFERENTES A LA TABLA USUARIO_SISTEMA
A FIN DE IDENTIFICAR CUANDO ALGÚN ATRIBUTO SEA ACTUALIZADO Y ALMACENAR EL VALOR ANTIGUO Y NUEVO*/
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('ID_USUARIO');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('NOMBRES');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('APELLIDOS');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('ID_ESTADO_USR');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('ID_ROL');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('ACCESO');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('ULTIMO_LOGIN');
INSERT INTO CAMPO_AFECTADO_USUARIO(DESCRIPCION) VALUES('ULTIMO_USUARIO_MODIFICADOR');


/*ESTADO_USUARIO: EL USUARIO PODRÁ ESTAR:
1.- ACTIVO
2.-INACTIVO
3.-BAJA(ELIMINADO= NO VISIBLE PARA EL USUARIO FINAL, PERO EXISTENTE AÚN EN LA BASE DE DATOS)*/
INSERT INTO ESTADO_USUARIO(NOMBRE)VALUES('ACTIVO');
INSERT INTO ESTADO_USUARIO(NOMBRE)VALUES('INACTIVO');
INSERT INTO ESTADO_USUARIO(NOMBRE)VALUES('BAJA');


/*ROL USUARIO:EXISTIRÀN LOS SIGUIENTES:

1.- ADMIN. DEL SISTEMA 
2.-JEFATURA
3.-Auxiliar Administrativo*/

insert into rol_usuario(DESCRIPCION) values('ADMINISTRADOR');
insert into rol_usuario(DESCRIPCION) values('JEFATURA');
insert into rol_usuario(DESCRIPCION) values('AUXILIAR_ADMINISTRATIVO');


/*SECCION: Se refiere a las secciones existentes en la Unidad de Tanatologìa Foresnse Metropolitana,
actualmente son las siguientes:
o	PCEN: (Patología Central), se refiere a la Sección de Medicina Forense Tanatológica.
o	OD: (Odontología), hace alusión a la Sección de Necro-Odontología.
o	HISTOCEN: (Histopatología Central), referente a la Sección de Histopatología.
o	ANTRO (Antropología), se refiere a la Sección de Antropología Forense.
o	ARQU(Arqueología), referente a la Sección de Arqueología Forense.
*/

INSERT INTO SECCION (NOMBRE,IDENTIFICADOR) VALUES('TANATOLOGÍA FORENSE METROPOLITANA','PCEN');
INSERT INTO SECCION (NOMBRE,IDENTIFICADOR) VALUES('ODONTOLOGÍA FORENSE','OD');
INSERT INTO SECCION (NOMBRE,IDENTIFICADOR) VALUES('HISTOPALOGÍA CENTRAL','HISTOCEN');
INSERT INTO SECCION (NOMBRE,IDENTIFICADOR) VALUES('ANTROPOLOGÍA FORENSE','ANTRO');
INSERT INTO SECCION (NOMBRE,IDENTIFICADOR) VALUES('ARQUEOLOGÍA FORENSE','ARQU');


/*CLASE DE GESTIÓN: DESCRIBE SI EL TIPO DE GESTIÓN ES:
o   PERITAJE
o   OFICIO*/

INSERT INTO CLASE_GESTION (NOMBRE) VALUES('PERITAJE');
INSERT INTO CLASE_GESTION (NOMBRE) VALUES('OFICIO');



/*TIPO_GESTION: 
o   NECROPSIA_MÉDICO_LEGAL
o	Ampliación.
o	Rectificación.
o	Reiteración de orden.
o	Búsqueda de personas desaparecidas.
o	Correlaciones de expedientes clínicos y necropsias.
o	Citaciones a debates (presenciales o videoconferencias).
o	Respuesta Administrativa (por parte de Jefatura de Tanatología Forense).
o   
*/

INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('NECROPSIA',1);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('AMPLIACION',2);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('RECTIFICACION',2);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('REITERACION',2);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('BÚSQUEDA',2);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('EXP.CLINICO',1);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('CITACION_DEBATE',2);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('RESP.ADMINISTRATIVA',2);
INSERT INTO TIPO_GESTION(NOMBRE,ID_CLASE_GESTION) VALUES('SG(ASISTENCIAS_JUDICIALES)',2);



/***********************************************************************************************************/
                                        /*LÓGICA DEL NEGOCIO*/
/***********************************************************************************************************/
/*CRUD USUASARIO : (CREACION, ACTUALIZACION Y BAJA DE USUARIOS EN EL SISTEMA*/
/***********************************************************************************************************/
                                        /*VISTAS CRUD USUARIO*/
/***********************************************************************************************************/

/*LA VISTA  NOS PERMITIRÀ MOSTRAR ÚNICAMENTE LOS DATOS 
DE LA TABLA USUARIO PARA NO USAR DE FORMA DIRECTA LA TABLA Y NO VULNERAR ESTA*/
DROP VIEW IF EXISTS VISTA_CRUD_USUARIO;
CREATE VIEW VISTA_CRUD_USUARIO(NIP,NOMBRES,APELLIDOS,ESTADO_USUARIO,ROL,ACCESO,ULTIMO_LOGIN)
AS
(SELECT US.ID_USUARIO,US.NOMBRES,US.APELLIDOS,EU.NOMBRE,R.DESCRIPCION,US.ACCESO,US.ULTIMO_LOGIN 
FROM USUARIO_SISTEMA US
INNER JOIN ESTADO_USUARIO EU
ON US.ID_ESTADO_USR=EU.ID_ESTADO_USR
INNER JOIN ROL_USUARIO R
ON US.ID_ROL=R.ID_ROL WHERE EU.NOMBRE<>'BAJA');


/***********************************************************************************************************/
                                        /*FUNCIONES CRUD USUARIO*/
/***********************************************************************************************************/


/*FUNCION PARA SABER  el rol de un usuario en específico*/
DROP FUNCTION IF EXISTS FUNCT_NOMBRE_ROL_USUARIO;
DELIMITER //
CREATE FUNCTION FUNCT_NOMBRE_ROL_USUARIO(NIP_URS INT) RETURNS VARCHAR(100)
BEGIN
    DECLARE DESCRIPCION_ROL VARCHAR(100);
    /*Valido que el usuario exista, y esté activo*/
    SET DESCRIPCION_ROL=(SELECT RU.DESCRIPCION FROM ROL_USUARIO RU
    INNER JOIN USUARIO_SISTEMA US ON 
    RU.ID_ROL = US.ID_ROL
    WHERE US.ID_USUARIO=NIP_URS AND  US.ID_ESTADO_USR=4);

    IF(DESCRIPCION_ROL!='') THEN
        RETURN DESCRIPCION_ROL;
    ELSE
        SIGNAL SQLSTATE '20005' SET MESSAGE_TEXT = "EL ROL O USUARIO 
        NO EXISTEN EN EL SISTEMA";
    END IF;
END// DELIMITER;


/*funcion para validar que existe el usuario que pretende modificar el estado de otro,
,si existe, devuelve 1 y sino 0, además que tenga privilegios de administrador*/
DROP FUNCTION  IF EXISTS FUNCT_EXISTE_USR_ADMIN;
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_USR_ADMIN(NIP_RESP INT) RETURNS INT
    BEGIN 
        /*Variable en la cual vamos a devolver el resultado de un count
        que realizaré para validar si un usuario existe en la bd y está activo*/
        DECLARE CONTAR_RESULTADO INT;
        /*Variable para capturar el rol de un usuario*/
        DECLARE ROL_USR_RESP VARCHAR(100);
        /*verifico si el usuario que quiere modificar el estado de otro,
        existe en la bd*/
        SET CONTAR_RESULTADO=(SELECT COUNT(*) FROM USUARIO_SISTEMA WHERE ID_USUARIO=NIP_RESP AND ID_ESTADO_USR<>6 AND ID_ESTADO_USR<>5);
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



/*función para determinar si existe ya un usuario con el nip en el sistema*/
DROP FUNCTION IF EXISTS FUNCT_EXISTE_USUARIO_SISTEMA;
DELIMITER //
CREATE FUNCTION FUNCT_EXISTE_USUARIO_SISTEMA(NIP_NUEVO INT) RETURNS INT
BEGIN
    SET @VERIFICAR_ID_USR=(SELECT COUNT(*) FROM USUARIO_SISTEMA WHERE ID_USUARIO=NIP_NUEVO);

    IF(@VERIFICAR_ID_USR>0) THEN
        RETURN @VERIFICAR_ID_USR;
    ELSE
        RETURN @VERIFICAR_ID_USR;
    END IF;
END// DELIMITER;



/*FUNCION PARA DEVOLVER EL ID DEL TIPO DE "ESTADO BAJA"*/
DROP FUNCTION IF EXISTS FUNCT_DEVOLVER_ESTADO_USR_BAJA;
DELIMITER //
CREATE FUNCTION FUNCT_DEVOLVER_ESTADO_USR_BAJA() RETURNS INT(03)
BEGIN
    DECLARE ID_ESTADO_BAJA INT;
    SET ID_ESTADO_BAJA=(SELECT ID_ESTADO_USR FROM ESTADO_USUARIO WHERE NOMBRE="BAJA");

    IF(ID_ESTADO_BAJA!='') THEN
        RETURN ID_ESTADO_BAJA;
    ELSE
        SIGNAL SQLSTATE '20014' SET MESSAGE_TEXT = "ERROR AL BUSCAR EL ESTADO DE USUARIO, CONTACTE AL DBA";
    END IF;
END// DELIMITER;




/***********************************************************************************************************/
                                        /*PROCEDIMIENTOS ALMACENADOS CRUD USUARIO*/
/***********************************************************************************************************/
/*Procedimiento almacenado para la creación de Un usuario del sistema,*/
DROP PROCEDURE IF EXISTS PA_CREAR_USUARIO_SISTEMA;
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_CREAR_USUARIO_SISTEMA`(IN NIP_NUEVO INT, IN NOMBRES_USR_NUEVO VARCHAR(200),IN APELLIDOS_USR_NUEVO VARCHAR(100),IN NOMBRE_ROL_USUARIO_NUEVO VARCHAR(100),IN CONTRASENIA_USR_NUEVO VARCHAR(100),IN NIP_ULT_USR_MOD INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
         SIGNAL SQLSTATE '20001' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de creación de usuario";
    END;
    START TRANSACTION;
        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR_ADMIN(NIP_ULT_USR_MOD));
        IF(@PRIVILEGIOS=1) THEN
            /*VERIFICAMOS SI EL USUARIO EXISTE EN EL SISTEMA*/
            SET @EXISTE_USUARIO = (SELECT FUNCT_EXISTE_USUARIO_SISTEMA(NIP_NUEVO));
            IF(@EXISTE_USUARIO=0) THEN
                /*EVALUAMOS QUE EL ROL EXISTE EN LA BD Y EXTRAEMOS EL ID DE ESTE*/
                SET @NROL_USUARIO=(SELECT ID_ROL FROM ROL_USUARIO 
                WHERE DESCRIPCION=NOMBRE_ROL_USUARIO_NUEVO LIMIT 1);
                /*SI NO NOS DEVUELVE NULL ó 0*/ 
                IF(@NROL_USUARIO IN(4,5,6)) THEN
                    INSERT INTO USUARIO_SISTEMA
                    (ID_USUARIO,NOMBRES,APELLIDOS,ID_ESTADO_USR,ID_ROL,ACCESO,ULTIMO_LOGIN,ULTIMO_USUARIO_MODIFICADOR)
                    VALUES
                    (NIP_NUEVO,NOMBRES_USR_NUEVO,APELLIDOS_USR_NUEVO,4,@NROL_USUARIO,CONTRASENIA_USR_NUEVO,NOW(),NIP_ULT_USR_MOD);
                ELSE
                SIGNAL SQLSTATE '20002' SET MESSAGE_TEXT = 'ROL INEXISTENTE';
                END IF;
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20003' SET MESSAGE_TEXT = 'El usuario ya existe, sino lo observa en la tabla, comuníquese con el desarrollador';
                ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20004' SET MESSAGE_TEXT='¡Usted no tiene privilegios de Administrador!';
            ROLLBACK;
        END IF;
END;

/*call PA_CREAR_USUARIO_SISTEMA(1759,'IRON','MAIDEN','ADMINISTRADOR','ROCK',1751);*/

/*procedimiento almacenado para la actualización de datos de un usuario*/
DROP PROCEDURE IF EXISTS PA_ACTUALIZAR_USUARIO_SISTEMA; 
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `PA_ACTUALIZAR_USUARIO_SISTEMA`(IN NIP_FIJO INT, IN NOMBRES_USR_EDITAR VARCHAR(100),
IN APELLIDOS_USR_EDITAR VARCHAR(100),IN NOMBRE_ROL_USUARIO_EDITAR VARCHAR(100),IN CONTRASENIA_USR_EDITAR VARCHAR(100),IN NIP_ULT_USR_MOD INT)
BEGIN
    /*variables para capturar los datos recuperados de la bd del usuario
    a través de un cursor*/
    DECLARE C_NIP INT(10);
    DECLARE C_NOMBRES VARCHAR(200);
    DECLARE C_APELLIDOS VARCHAR(200);
    DECLARE C_ID_ROL_USUARIO INT(3);
    DECLARE C_ACCESO VARCHAR(200);
    DECLARE C_NIP_ULT_USR_MODIFICADOR INT(10);
    /*variable para cerrar el loop*/
    DECLARE FIN_LOOP INTEGER DEFAULT 0;
    /*creo un cursor para recuperar los datos del usuario a actualizar (datos)*/
    DECLARE CURSOR_SELECCION_USUARIO CURSOR FOR
    SELECT ID_USUARIO,NOMBRES,APELLIDOS,ID_ROL,ACCESO,ULTIMO_USUARIO_MODIFICADOR
    FROM USUARIO_SISTEMA WHERE ID_USUARIO=NIP_FIJO;

    /*VARIABLE PARA CONTROLAR EL FINAL DE RECORRIDO DEL CURSOR*/
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET FIN_LOOP=1; 
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
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR_ADMIN(NIP_ULT_USR_MOD));
        IF(@PRIVILEGIOS=1) THEN
            /*EVALUAMOS QUE EL ROL EXISTE EN LA BD*/
            SET @NROL_USUARIO=(SELECT ID_ROL FROM ROL_USUARIO 
            WHERE DESCRIPCION=NOMBRE_ROL_USUARIO_EDITAR LIMIT 1);
            /*SI EL ID DE ROL DE USUARIO SE ENCUENTRA DENTRO DE LOS PERMITIDOS*/ 
            IF(@NROL_USUARIO IN(4,5,6)) THEN
                /*abrimos el cursor*/
                OPEN CURSOR_SELECCION_USUARIO;
                    CICLO: LOOP
                    FETCH CURSOR_SELECCION_USUARIO INTO C_NIP,C_NOMBRES, C_APELLIDOS,
                    C_ID_ROL_USUARIO,C_ACCESO, C_NIP_ULT_USR_MODIFICADOR;
                        /*comprobamos si llegamos al final de los registros obtenidos del cursor*/
                        IF FIN_LOOP = 1 THEN
                            LEAVE CICLO;
                        END IF;
                        /*HARÉ VALIDACIÓN DE CAMPO POR CAMPO PARA IDENTIFICAR SI VARIA EL VALOR ANTIGUO
                        DE CADA UNO CON EL NUEVO ENVIADO POR EL USUARIO DESDE LA VISTA*/
                        /*SI SE ACTUALIZA EL NOMBRE DEL USUARIO*/
                        IF(NOMBRES_USR_EDITAR<>C_NOMBRES) THEN
                            UPDATE USUARIO_SISTEMA SET NOMBRES=NOMBRES_USR_EDITAR WHERE ID_USUARIO=NIP_FIJO;
                        END IF;
                        /*SI LOS APELLIDOS O APELLIDO SON O ES ACTUALIZADO*/
                        IF(APELLIDOS_USR_EDITAR<>C_APELLIDOS) THEN
                            UPDATE USUARIO_SISTEMA SET APELLIDOS=APELLIDOS_USR_EDITAR WHERE ID_USUARIO=NIP_FIJO;
                        END IF;
                        /*SI EL ROL ES ALTERADO*/
                        IF(C_ID_ROL_USUARIO<>@NROL_USUARIO) THEN
                            UPDATE USUARIO_SISTEMA SET  ID_ROL=@NROL_USUARIO WHERE ID_USUARIO=NIP_FIJO;
                        END IF;
                        /*SI LA CONTRASEÑA ES CAMBIADA*/
                        IF(C_ACCESO<>CONTRASENIA_USR_EDITAR) THEN
                            UPDATE USUARIO_SISTEMA SET ACCESO=CONTRASENIA_USR_EDITAR WHERE ID_USUARIO=NIP_FIJO;
                        END IF;
                        /*DEBEMOS REGISTAR QUE USUARIO CON PRIVILEGIOS DE ADMIN 
                        FUE EL ÚLITMO EN EDITAR A OTRO USUARIO*/
                        IF(C_NIP_ULT_USR_MODIFICADOR<>NIP_ULT_USR_MOD) THEN
                            UPDATE USUARIO_SISTEMA SET ULTIMO_USUARIO_MODIFICADOR=C_NIP_ULT_USR_MODIFICADOR
                            WHERE ID_USUARIO=NIP_FIJO;
                        END IF;         
                    END LOOP CICLO;
                CLOSE CURSOR_SELECCION_USUARIO;
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20007' SET MESSAGE_TEXT = 'ROL INEXISTENTE';
            ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20008' SET MESSAGE_TEXT = '¡USTED NO TIENE PRIVILEGIOS PARA RELIZAR ESTAS ACCIONES!';
        END IF;    
END;
DELIMITER //

/*CALL PA_ACTUALIZAR_USUARIO_SISTEMA(1754,'PEDRO','APOSTOL','JEFATURA','DOS MIL VEINTIDÓS',1751);*/



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
         SIGNAL SQLSTATE '20009' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de actualización de estado del usuario";
    END;

    START TRANSACTION;
        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR_ADMIN(NIP_USR_RESP));
        IF(@PRIVILEGIOS=1) THEN
            UPDATE USUARIO_SISTEMA SET ID_ESTADO_USR=ESTADO_USUARIO WHERE ID_USUARIO=NIP_FIJO;
            UPDATE USUARIO_SISTEMA SET ULTIMO_USUARIO_MODIFICADOR=NIP_USR_RESP WHERE ID_USUARIO=NIP_FIJO;
        ELSE
            SIGNAL SQLSTATE '20010' SET MESSAGE_TEXT = "¡Usted no tiene privilegios de Administrador!";
            ROLLBACK;
        END IF;
    COMMIT;
    END;

   /* CALL PA_ACTUALIZACION_ESTADO_USR(1754,5,1751);*/



/*PROCEDIMIENTO ALMACENAOD PARA DAR DE BAJA A UN USUARIO DEL SISTEMA*/
DROP PROCEDURE  IF EXISTS PA_BAJA_USR_SISTEMA;
DELIMITER //
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
         SIGNAL SQLSTATE '20011' SET MESSAGE_TEXT = "errror durante
         la ejecución del procedimiento de BAJA DEL USUARIO";
    END;

    START TRANSACTION;
        /*VERIFICAMOS SI EL USUARIO QUE QUIERE CREAR OTRO ESTÁ ACTIVO Y TENGA PRIVILEGIOS DE ADMIN, LUEGO VALIDAREMOS EL ROL*/
        SET @PRIVILEGIOS=(SELECT FUNCT_EXISTE_USR_ADMIN(NIP_USR_RESP));
        IF(@PRIVILEGIOS=1) THEN
            /*RECUPERO EL ID DEL ESTADO "BAJA" CON UNA FUNCIÓN*/
            SET ID_ESTADO_BAJA=(SELECT FUNCT_DEVOLVER_ESTADO_USR_BAJA());
            /*valido si existe el usuario responsable de la gestión,
            SI existe y tiene rol de admin y está activo, devuelve 1, sino 0*/
            SET VALIDAR_ROL=(SELECT FUNCT_EXISTE_USR_ADMIN (NIP_USR_RESP));
            IF(VALIDAR_ROL=1) THEN
                UPDATE USUARIO_SISTEMA SET ID_ESTADO_USR=ID_ESTADO_BAJA WHERE ID_USUARIO=NIP_FIJO;
                UPDATE USUARIO_SISTEMA SET ULTIMO_USUARIO_MODIFICADOR=NIP_USR_RESP WHERE ID_USUARIO=NIP_FIJO;
                COMMIT;
            ELSE
                SIGNAL SQLSTATE '20012' SET MESSAGE_TEXT = "El usuario no tiene privilegios, o no existe
                en la base de datos o está inactivo";
                ROLLBACK;
            END IF;
        ELSE
            SIGNAL SQLSTATE '20013' SET MESSAGE_TEXT="¡Usted no tiene privilegios para realizar esta acción!";
        END IF;
    END// DELIMITER;

/**/ 
/*CALL PA_BAJA_USR_SISTEMA(1754,1751);*/



/*******************************************************************************/
                        /*TRIGGERS CRUD USUARIO */
/*******************************************************************************/


/*TRIGGER PARA REGISTRAR LA CREACIÓN DE UN USUARIO (INSERCIÓN DE USUARIO), SOLO 
SE ALMACENA EL "MOVIMIENTO" YA EN LA ACTUALIZACIÓN DE DATOS, SERÁ OTRO TRIGGER
 QUE SE DISPARARÁ*/
DROP TRIGGER IF EXISTS AI_USUARIO_SISTEMA;
DELIMITER// 
/*Trigger para registar la creación de un nuevo usuario*/
CREATE DEFINER=`root`@`localhost` TRIGGER AI_USUARIO_SISTEMA
    AFTER INSERT ON USUARIO_SISTEMA
    FOR EACH ROW
    BEGIN
      INSERT INTO BITACORA_USUARIO(ID_USR_AFECTADO,ID_TIPO_MOVIMIENTO,
      FECHA_MOVIMIENTO,ID_USR_RESPONSABLE)
      VALUES(new.ID_USUARIO,4,now(),new.ULTIMO_USUARIO_MODIFICADOR);
  	END// DELIMITER;





