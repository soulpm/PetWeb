use maskotaweb_db;
-- ---------------------------------------------- 
-- LISTA PACIENTES INGRESADOS AL SISTEMA
-- ----------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_PAT_LST;
DELIMITER $$
CREATE PROCEDURE SP_VET_PAT_LST(
	IN p_token 		VARCHAR(255),	-- TOKEN USUARIO
	IN P_ID_PAT 	INT, 			-- ID PACIENTE
	IN P_ID_KIND 	INT, 			-- TIPO PACIENTE
	IN P_NAME 		VARCHAR(85) 	-- NOMBRE PACIENTE
)
BEGIN	
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		SELECT 
		P.ID_PAT			ID,
		P.ID_KIND_PAT		ID_TIPO,
		(SELECT KP.NAME FROM vet_kind_patient KP WHERE KP.id_kind_pat = P.id_kind_pat) TIPO,
		P.NAME				NOMBRES,
		P.SEX				SEXO,
		P.BREED				RAZA,
		P.COLOR				COLOR,
		P.DATE_BORN			FECHA_NACIMIENTO,
		P.YEAR				YEARS,
		P.MONTH				MONTHS
		FROM vet_patient P
		WHERE 
		ID_PAT 			= CASE WHEN P_ID_PAT 	IS NULL OR P_ID_PAT 	= 0  THEN ID_PAT 		ELSE P_ID_PAT 	END AND
		ID_KIND_PAT 	= CASE WHEN P_ID_KIND 	IS NULL OR P_ID_KIND 	= 0  THEN ID_KIND_PAT 	ELSE P_ID_KIND 	END AND
		NAME 			like CASE WHEN P_NAME 		IS NULL OR P_NAME 		= '' THEN NAME 	 	ELSE  CONCAT('%',P_NAME,"%") END ;
	END IF;
END$$
DELIMITER ;
-- ------------------------------------------------------------ 
-- LISTA PROPIETARIOS DEL PACIENTE INGRESADOS AL SISTEMA
-- ------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_PAT_OWN_LST;
DELIMITER $$
CREATE PROCEDURE SP_VET_PAT_OWN_LST(
	IN p_token VARCHAR(255),		-- TOKEN USUARIO
	IN P_ID_PAT 	INT, 			-- ID PACIENTE
	IN P_ID_USR 	INT, 			-- TIPO PACIENTE
	IN P_NAME 		VARCHAR(85) 	-- NOMBRE PACIENTE
)
BEGIN	
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		SELECT 
		P.ID_PAT			ID,
		U.NAMES				NOMBRE_PROPIETARIO
		/*
		P.ID_KIND_PAT		ID_TIPO,
		(SELECT KP.NAME FROM vet_kind_patient KP WHERE KP.id_kind_pat = P.id_kind_pat) TIPO,
		P.NAME				NOMBRES,
		P.SEX				SEXO,
		P.BREED				RAZA,
		P.COLOR				COLOR,
		P.DATE_BORN			FECHA_NACIMIENTO,
		P.AGE				EDAD
		*/
		FROM vet_patient P 
		INNER JOIN 
		vet_patient_owners PO ON
		P.ID_PAT = PO.ID_PAT
		INNER JOIN 
		vet_patient_owners O ON
		PO.ID_PAT = O.ID_PAT
		INNER JOIN 
		sys_usr U ON
		P.ID_USR = U.ID_USR
		WHERE 
		P.ID_PAT 		= CASE WHEN P_ID_PAT 	IS NULL OR P_ID_PAT 	= 0  THEN P.ID_PAT 		ELSE P_ID_PAT 	END AND
		PO.ID_USR 		= CASE WHEN P_ID_KIND 	IS NULL OR P_ID_KIND 	= 0  THEN ID_KIND_PAT 	ELSE P_ID_KIND 	END AND
		P.NAME 			= CASE WHEN P_NAME 		IS NULL OR P_NAME 		= '' THEN NAME 	 		ELSE P_NAME 	END ;
	END IF;
END$$
DELIMITER ;

-- ---------------------------------------------------------------------------------------------------------------
-- INSERT PATIENT
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_PAT_INS;
DELIMITER //
CREATE PROCEDURE SP_VET_PAT_INS(
IN  P_TOKEN		VARCHAR(60)		,-- TOKEN USER
IN  P_KIND		INT	 			,-- KIND 
IN  P_NAME 		VARCHAR(80)	 	,-- NAMES
IN  P_SEX		VARCHAR(60)		,-- SEX
IN  P_BREED		VARCHAR(85)		,-- RAZA 
IN  P_COLOR 	VARCHAR(15)		,-- COLOR
IN  P_DATE_BORN DATE			,-- FECHA NACIMIENTO
IN  P_YEAR 		INT				,-- EDAD
IN  P_MONTH		INT				,-- MESES
OUT PMSG 		VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		-- SELECT COUNT(*) INTO @NUM FROM vet_patient WHERE  NAME = P_NAME AND ID_KIND_PAT = P_KIND;	
		-- IF @NUM =0 THEN
			INSERT INTO vet_patient(ID_KIND_PAT,NAME,SEX,BREED,COLOR,DATE_BORN,YEAR,MONTH)
			VALUES (P_KIND,P_NAME,P_SEX,P_BREED,P_COLOR,P_DATE_BORN,P_YEAR,P_MONTH);		
			SET PMSG = 'Paciente Registrado';
	   -- ELSE 
			-- SET PMSG = 'Error, Ya existe un paciente con el nombre ingresado';
	   -- END IF;                    
    ELSE 
			SET PMSG = 'Error, No tiene permisos para realizar la insercion';
	END IF;
END//
DELIMITER ;		

-- ---------------------------------------------------------------------------------------------------------------
-- UPDATE PATIENT
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_PAT_UPD;
DELIMITER //
CREATE PROCEDURE SP_VET_PAT_UPD(
IN  P_TOKEN		VARCHAR(60)		,-- TOKEN USER
IN  P_ID		INT	 			,-- ID PATIENT
IN  P_KIND		INT	 			,-- KIND 
IN  P_NAME 		VARCHAR(80)	 	,-- NAMES
IN  P_SEX		VARCHAR(60)		,-- SEX
IN  P_BREED		VARCHAR(85)		,-- RAZA 
IN  P_COLOR 	VARCHAR(15)		,-- COLOR
IN  P_DATE_BORN DATE			,-- FECHA NACIMIENTO
IN  P_YEAR 		INT				,-- EDAD
IN  P_MONTH		INT				,-- MESES
OUT PMSG 		VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
	   -- SELECT COUNT(*) INTO @NUM FROM vet_patient WHERE  NAME = P_NAME AND ID_KIND_PAT = P_KIND AND ID_PAT <> P_ID;	
	   -- IF @NUM =0 THEN
			UPDATE vet_patient SET 
				ID_KIND_PAT = P_KIND,
				NAME 		= P_NAME,
				SEX	 		= P_SEX,
				BREED		= P_BREED,
				COLOR		= P_COLOR,
				DATE_BORN	= P_DATE_BORN,
				YEAR		= P_YEAR,
				MONTH		= P_MONTH
			WHERE ID_PAT 	= P_ID;
			SET PMSG = 'Paciente Modificado';
	   /*ELSE 
			SET PMSG = 'Error, Ya existe un paciente con el nombre ingresado';
	   END IF;*/                    
    ELSE 
			SET PMSG = 'Error, No tiene permisos para realizar la modificacion';
	END IF;
END//
DELIMITER ;		
-- ---------------------------------------------------------------------------------------------------------------
-- INSERT PATIENT OWNER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_PAT_OWN_INS;
DELIMITER //
CREATE PROCEDURE SP_VET_PAT_OWN_INS(
IN  P_TOKEN		VARCHAR(60)		,-- TOKEN USER
IN  P_ID_USR	INT	 			,-- ID USER 
IN  P_ID_PAT    INT 			,-- ID PATIENT
OUT PMSG 		VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		INSERT INTO vet_patient_owners (ID_USR,ID_PAT)
		VALUES (P_ID_USR,P_ID_PAT);
    ELSE 
			SET PMSG = 'Error, No tiene permisos para realizar la insercion';
	END IF;
END//
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- INSERT PATIENT OWNER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_PAT_LAST_OWNER;
DELIMITER //
CREATE PROCEDURE SP_USR_PAT_LAST_OWNER(
OUT PMSG 		VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	INSERT INTO vet_patient_owners (ID_USR,ID_PAT)
	VALUES (
		(select ID_USR from sys_usr where ID_USR = (SELECT MAX(U.ID_USR) FROM sys_usr U)),
		(select ID_PAT from vet_patient where ID_PAT = (SELECT MAX(V.ID_PAT) FROM vet_patient V))
	);
    SET PMSG = 'Asignación Propietario Correcta';
END//
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- DELETE PATIENT OWNER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_PAT_OWN_DEL;
DELIMITER //
CREATE PROCEDURE SP_VET_PAT_OWN_DEL(
IN  P_TOKEN		VARCHAR(60)		,-- TOKEN USER
IN	P_USR		INT				,-- ID USER
IN  P_ID_PAT	INT	 			,-- ID PATIENT 
OUT PMSG 		VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		DELETE FROM  vet_patient_owners WHERE ID_PAT = P_ID_PAT;
    ELSE 
			SET PMSG = 'Error, No tiene permisos para realizar la insercion';
	END IF;
END//
DELIMITER ;

-- ---------------------------------------------------------------------------------------------------------------
-- LIST SIGN CLINICS
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_CLIN_SIGN_LST;
DELIMITER $$
CREATE PROCEDURE SP_CLIN_SIGN_LST(
	IN P_TOKEN			VARCHAR(60),	-- TOKEN	
	IN P_ID  			INT, 			-- ID
	IN P_NAME 			VARCHAR(100), 	-- VALUE 
	IN P_TYPE_1			INT,			-- TYPE 1
	IN P_TYPE_2			INT				-- TYPE 2
)
BEGIN	
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		SELECT 
		ID_CLIN_SGN, 
		VALUE 
		FROM vet_clinic_signs WHERE 
		TYPE_1 		= P_TYPE_1 AND 
		TYPE_2		= CASE WHEN P_TYPE_2 = 0 THEN TYPE_2 ELSE P_TYPE_2 END  AND 
		ID_CLIN_SGN = CASE WHEN P_ID = 0 OR P_ID IS NULL THEN ID_CLIN_SGN ELSE P_ID END AND
		VALUE 		= CASE WHEN P_NAME = '' OR P_NAME = '' THEN VALUE ELSE CONCAT('%',VALUE,'%') END; 
	END IF;
END$$
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- INSERT ATTENTION
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_HIS_CLI_INS;
DELIMITER //
CREATE PROCEDURE SP_VET_HIS_CLI_INS(
IN P_TOKEN				  VARCHAR(60)		,-- TOKEN USER
IN P_ID_PAT               int,
IN P_ID_CLIN_SGN          VARCHAR(250),
IN P_ID_USR_MED           int,
IN P_DATE_REG             date,
IN P_STATURE              float,
IN P_WEIGHT               float,
IN P_TEMPERATURE		  VARCHAR(100),
IN P_RECOMMEND			  VARCHAR(250), 
IN P_DIAGNOSTIC           varchar(250),
IN P_NEXT_DATE            date,
IN P_TREATMENT            varchar(250),
IN P_VACCINE              varchar(250),
IN P_CHEMOTHERAPY         varchar(250),
IN P_PAYMENT              float,
IN P_VACCINE_COMPLETED	INT,
IN P_DESPARACITADO		INT,
IN P_WITH_OPERATION		INT,
IN P_SOCIAL_KNOWN		INT,
IN P_IS_PULGAS			INT,
IN P_IVE_GARRAPATA		INT,
IN P_IVE_HONGOS			INT,	
IN P_IVE_OTITIS			INT,	
IN P_IVE_BANIO_STD		INT,
IN P_IVE_BANIO_MED		INT,
IN P_IVE_CORTE			INT,
IN P_IVE_PROMO_SUCCESS	INT,
OUT PMSG 				  VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
			
		INSERT INTO vet_history_clinic
		(  ID_PAT               ,
		   ID_CLIN_SGN          ,
		   ID_USR_MED           ,
		   DATE_REG             ,
		   STATURE              ,
		   WEIGHT               ,
		   DIAGNOSTIC           ,
		   NEXT_DATE            ,
		   TREATMENT            ,
		   VACCINE              ,
		   CHEMOTHERAPY         ,
		   PAYMENT 				,
		   TEMPERATURE			,
		   RECOMMEND			,
		   VACCINE_COMPLETED	,
		   DESPARACITADO		,
		   WITH_OPERATION		,
		   SOCIAL_KNOWN			,
		   IS_PULGAS			,
		   IVE_GARRAPATA		,
		   IVE_HONGOS			,
		   IVE_OTITIS			,
		   IVE_BANIO_STD		,
		   IVE_BANIO_MED		,
		   IVE_CORTE			,
		   IVE_PROMO_SUCCESS	
		 )
		VALUES 
		(
		   P_ID_PAT               	,
		   P_ID_CLIN_SGN          	,
		   P_ID_USR_MED           	,
		   P_DATE_REG             	,
		   P_STATURE              	,
		   P_WEIGHT               	,
		   P_DIAGNOSTIC           	,
		   P_NEXT_DATE            	,
		   P_TREATMENT            	,
		   P_VACCINE              	,
		   P_CHEMOTHERAPY         	,
		   P_PAYMENT			  	,
		   P_TEMPERATURE			,
		   P_RECOMMEND				,
		   P_VACCINE_COMPLETED		,
		   P_DESPARACITADO			,
		   P_WITH_OPERATION			,
		   P_SOCIAL_KNOWN			,
		   P_IS_PULGAS				,
		   P_IVE_GARRAPATA			,
		   P_IVE_HONGOS				,
		   P_IVE_OTITIS				,
		   P_IVE_BANIO_STD			,
		   P_IVE_BANIO_MED			,
		   P_IVE_CORTE				,
		   P_IVE_PROMO_SUCCESS	
		);
		SET PMSG = 'Atencion registrada correctamente';
    ELSE 
			SET PMSG = 'Error, No tiene permisos para realizar la insercion';
	END IF;
END//
DELIMITER ;		
-- ---------------------------------------------------------------------------------------------------------------
-- UPDATE ATTENTION
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_HIS_CLI_UPD;
DELIMITER //
CREATE PROCEDURE SP_VET_HIS_CLI_UPD(
IN P_TOKEN		VARCHAR(60)		,-- TOKEN USER
IN P_ID_HIS				  int,
IN P_ID_PAT               int,
IN P_ID_CLIN_SGN          VARCHAR(250),
IN P_ID_USR_MED           int,
IN P_DATE_REG             date,
IN P_STATURE              float,
IN P_WEIGHT               float,
IN P_DIAGNOSTIC           varchar(250),
IN P_NEXT_DATE            date,
IN P_TREATMENT            varchar(250),
IN P_VACCINE              varchar(250),
IN P_CHEMOTHERAPY         varchar(250),
IN P_PAYMENT              float,
OUT PMSG 				  VARCHAR(255)	 -- MESSAGE
)
 BEGIN
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
			
		UPDATE vet_history_clinic SET
		   ID_PAT               = P_ID_PAT,
		   ID_CLIN_SGN          = P_ID_CLIN_SGN,
		   ID_USR_MED           = P_ID_USR_MED,
		   DATE_REG             = P_DATE_REG,
		   STATURE              = P_STATURE,
		   WEIGHT               = P_WEIGHT,
		   DIAGNOSTIC           = P_DIAGNOSTIC,
		   NEXT_DATE            = P_NEXT_DATE,
		   TREATMENT            = P_TREATMENT,
		   VACCINE              = P_VACCINE,
		   CHEMOTHERAPY         = P_CHEMOTHERAPY,
		   PAYMENT 				= P_PAYMENT
		WHERE ID_HIS = P_ID_HIS;
		SET PMSG = 'La atencion ha sido modificada';
    ELSE 
		SET PMSG = 'Error, No tiene permisos para realizar la modificacion';
	END IF;
END//
DELIMITER ;		
-- ---------------------------------------------- 
-- LISTA HISTORIA CLINICA
-- ----------------------------------------------
DROP PROCEDURE IF EXISTS SP_VET_HIS_CLI_LST;
DELIMITER $$
CREATE PROCEDURE SP_VET_HIS_CLI_LST(
	IN p_token 		VARCHAR(255),	-- TOKEN USUARIO
	IN P_ID_HIS_CLI	INT, 			-- ID HISTORIA CLINICA
	IN P_ID_PAT 	INT 			-- ID PACIENTE
)
BEGIN	
	DECLARE P_ID_USR_SSN	INT;
	SELECT FN_IS_USER_AUTHORIZED(p_token) INTO P_ID_USR_SSN;
	IF P_ID_USR_SSN >0 THEN
		SELECT
		H.ID_HIS			   ,
		H.ID_PAT               ,
		P.NAME	PATIENT		   ,
		H.ID_CLIN_SGN          ,
		H.ID_USR_MED           ,
		U.NAMES	 MEDIC         ,
		H.DATE_REG             ,
		H.STATURE              ,
		H.WEIGHT               ,
		H.TEMPERATURE		   ,
		H.RECOMMEND			   ,
		H.DIAGNOSTIC           ,
		H.NEXT_DATE            ,
		H.TREATMENT            ,
		H.VACCINE              ,
		H.CHEMOTHERAPY         ,
	    H.PAYMENT			   ,
		CASE WHEN H.VACCINE_COMPLETED 	= 1 THEN 'Si' ELSE 'NO' END VACCINE_COMPLETED,
		CASE WHEN H.DESPARACITADO 		= 1 THEN 'Si' ELSE 'NO' END DESPARACITADO,
		CASE WHEN H.WITH_OPERATION 		= 1 THEN 'Si' ELSE 'NO' END WITH_OPERATION,
		CASE WHEN H.IS_PULGAS 			= 1 THEN 'Si' ELSE 'NO' END IS_PULGAS,
		CASE WHEN H.IVE_GARRAPATA 		= 1 THEN 'Si' ELSE 'NO' END IVE_GARRAPATA,
		CASE WHEN H.IVE_HONGOS 			= 1 THEN 'Si' ELSE 'NO' END IVE_HONGOS,
		CASE WHEN H.IVE_OTITIS 			= 1 THEN 'Si' ELSE 'NO' END IVE_OTITIS,
		CASE WHEN H.IVE_BANIO_STD 		= 1 THEN 'Si' ELSE 'NO' END IVE_BANIO_STD,
		CASE WHEN H.IVE_BANIO_MED 		= 1 THEN 'Si' ELSE 'NO' END IVE_BANIO_MED,
		CASE WHEN H.IVE_CORTE 			= 1 THEN 'Si' ELSE 'NO' END IVE_CORTE,
		CASE WHEN H.IVE_PROMO_SUCCESS 	= 1 THEN 'Si' ELSE 'NO' END IVE_PROMO_SUCCESS,
		H.SOCIAL_KNOWN		
		FROM vet_history_clinic H, sys_usr U, vet_patient P
		WHERE 
		H.ID_USR_MED 		= U.ID_USR AND
		H.ID_PAT			= P.ID_PAT AND 
		H.ID_PAT 			= CASE WHEN P_ID_PAT 	IS NULL OR P_ID_PAT 	 = 0  THEN H.ID_PAT ELSE P_ID_PAT 	END  AND 
		H.ID_HIS			= CASE WHEN P_ID_HIS_CLI IS NULL OR P_ID_HIS_CLI = 0  THEN H.ID_HIS	ELSE P_ID_HIS_CLI	END
		ORDER BY H.ID_HIS DESC;
	END IF;
END$$
DELIMITER ;
















