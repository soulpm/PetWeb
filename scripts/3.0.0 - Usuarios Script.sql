use maskotaweb_db;
-- ---------------------------------------------------------------------------------------------------------------
-- LOGIN USER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_LOGIN;
	DELIMITER //
	CREATE PROCEDURE SP_USR_LOGIN(
	IN p_identification VARCHAR(60),IN p_password VARCHAR(60),OUT PMSG VARCHAR(255))
	 BEGIN
		DECLARE _FOUND INT;
		SET _FOUND = 0;
		SELECT COUNT(1) INTO _FOUND FROM sys_usr
		WHERE (identification = p_identification OR sha1(nif) = p_identification) AND password = p_password;
		IF _FOUND = 1 THEN
			SELECT id_par INTO @STATE_BLOQ FROM cfg_par WHERE  VAL_2	= 'Bloqueado' AND VAL_1 = 'STD';
            -- SELECT id_par INTO @STATE_SESION FROM CFG WHERE CFGDN1 	= 'Logueado' AND CFGCM = 'SSN';
            SELECT state INTO @_STATE FROM sys_usr WHERE (identification = p_identification OR sha1(nif) = p_identification) AND password = p_password;
            SELECT id_rol  INTO @_PROFILE FROM sys_usr WHERE (identification = p_identification OR sha1(nif) = p_identification) AND password= p_password ;
		    SET @COUNT_USR_SERV = 0;
            IF @_STATE <> @STATE_BLOQ THEN
				
				SELECT ADDTIME(now(), '00:45:00') INTO @TIME_EXPIRE; 
				select round(RAND()*99999999) INTO @RANDOM_VALUE;
				-- UPDATE STATE FOR USER
				/*SELECT id_par INTO @id_parametro FROM cfg_par WHERE VAL_1 = 'LGN' AND VAL_2 = 'Logueado';
				UPDATE 	sys_usr SET state = @id_parametro
				WHERE (identification = p_identification OR sha1(nif) = p_identification)  AND password = p_password;
				*/
				-- INSERT TOKEN FOR USER 
				SELECT DISTINCT U.ID_USR INTO @USER_ID_SELECT
				FROM sys_usr U WHERE (identification = p_identification OR sha1(nif) = p_identification)  AND password = p_password;
					
				SELECT COUNT(*) INTO @FIND_TOKEN_USER FROM cfg_acc_sys WHERE ID_USR = @USER_ID_SELECT;
				IF @FIND_TOKEN_USER = 0 THEN 
					INSERT INTO cfg_acc_sys (ACCESS_TOKEN,ID_USR,EXPIRES,SCOPE) VALUES 
					( 
					  sha1(concat
						(
							@USER_ID_SELECT
						   ,@RANDOM_VALUE
						)
					  ),
						@USER_ID_SELECT,
						@TIME_EXPIRE, 
						'Sistema Grifos Peruanos'					
					);
				END IF;
				SELECT ACCESS_TOKEN AS ID  FROM cfg_acc_sys WHERE ID_USR = @USER_ID_SELECT;
				SET PMSG = 'OK';
			ELSE	
				SET PMSG = 'Error, su cuenta se encuentra deshabilitada comuniquese con el area de sistemas.';
			END IF;
		ELSE
			SET PMSG = 'Error, Usuario no valido';
		END IF;
	 END //
	DELIMITER ;
-- call SP_LOGIN_USR('saul','saul',@msg);
-- call SP_LOGIN_USR(sha1('servicentroramirez'),sha1('servicentroramirez'),'000009','',@msg);

-- ---------------------------------------------------------------------------------------------------------------
-- LOGOUT USER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_LOGOUT;
DELIMITER //
CREATE PROCEDURE SP_USR_LOGOUT(
IN p_token VARCHAR(60))
BEGIN
	SELECT ID_PAR INTO @STATE_ACTIVE FROM cfg_par WHERE VAL_1 = 'STD' AND VAL_2 = 'Activo'; 
	SELECT id_usr INTO @P_ID_USR FROM cfg_acc_sys WHERE ACCESS_TOKEN = p_token;
	UPDATE sys_usr SET STATE = @STATE_ACTIVE WHERE ID_USR = @P_ID_USR;
	DELETE FROM cfg_acc_sys WHERE ACCESS_TOKEN = p_token;
END //
DELIMITER ;


-- ---------------------------------------------------------------------------------------------------------------
-- USER ACCOUNT
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_ACCOUNT;
DELIMITER //
CREATE PROCEDURE SP_USR_ACCOUNT(
IN p_token VARCHAR(255))
 BEGIN
		SELECT 
			p_token			 																as ID,
			U.ID_ROL																		as ID_ROL,
			(SELECT R.NAME FROM sys_rol R WHERE R.ID_ROL = U.ID_ROL) 						as ROL,
			U.NAMES 																		as NAMES,
			U.EMAIL																			as EMAIL,
			(SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF AND C.VAL_1 = 'TNIF') as TIPONIF, 
			U.NIF 																			as NIF,
			U.PHOTO 																		as PHOTO
			FROM sys_usr U WHERE id_usr = (
				SELECT id_usr FROM cfg_acc_sys WHERE ACCESS_TOKEN = p_token
			);
END //
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- LIST ROL BY TOKEN USER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_ROL;
DELIMITER $$
CREATE PROCEDURE SP_USR_ROL(
	IN P_TOKEN			VARCHAR(60) -- TOKEN	
)
BEGIN	
	DECLARE P_ID_USR_SSN INT;
	DECLARE P_ID_ROL_SSN INT;
	
	SELECT ID_USR INTO 	P_ID_USR_SSN FROM cfg_acc_sys WHERE ACCESS_TOKEN = P_TOKEN;
	SELECT ID_ROL INTO 	P_ID_ROL_SSN FROM sys_usr WHERE ID_USR = P_ID_USR_SSN;
	
	IF 	  P_ID_ROL_SSN = 1 THEN
		SELECT ID_ROL,NAME  from sys_rol WHERE STATE = 1;
	ELSEIF P_ID_ROL_SSN = 2 THEN
		SELECT ID_ROL,NAME  from sys_rol WHERE STATE = 1 AND ID_ROL IN (4);
	ELSEIF P_ID_ROL_SSN = 3 THEN
		SELECT ID_ROL,NAME  from sys_rol WHERE STATE = 1 AND ID_ROL IN (4);
	END IF;
	
END$$
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- LIST USER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_LST;
DELIMITER $$
CREATE PROCEDURE SP_USR_LST(
	IN P_TOKEN			VARCHAR(60),	-- TOKEN	
	IN P_ID  			INT, 			-- ID
	IN P_NAME 			VARCHAR(100), 	-- NAME
	IN P_ROLE  			INT,  			-- ROLE
	IN P_TYPE_DOC 	   	INT,  			-- TIPO NIF
	IN P_STATE      	INT   			-- ESTADO 
)
BEGIN	
	DECLARE P_ID_USR_SSN INT;
	DECLARE P_ID_ROL_SSN INT;
	
	SELECT ID_USR INTO 	P_ID_USR_SSN FROM cfg_acc_sys WHERE ACCESS_TOKEN = P_TOKEN;
	SELECT ID_ROL INTO 	P_ID_ROL_SSN FROM sys_usr WHERE ID_USR = P_ID_USR_SSN;
	
	IF 	  P_ID_ROL_SSN = 1 THEN		-- ADMINISTRADOR
		CALL SP_USR_ADM_SYS_LST(P_ID,P_NAME,P_ROLE,P_TYPE_DOC,P_STATE);
	ELSEIF P_ID_ROL_SSN = 2 THEN	-- MEDICO
		CALL SP_USR_MED_LST(P_ID_USR_SSN,P_NAME,P_ROLE,P_TYPE_DOC,P_STATE);
	ELSEIF P_ID_ROL_SSN = 3 THEN	-- RECEPCIONISTA
		CALL SP_USR_ADM_LST(P_ID_USR_SSN,P_NAME,P_ROLE,P_TYPE_DOC,P_STATE);
		-- CALL SP_USR_SALESMAN_LST(P_ID_USR_SSN,P_NAME,P_ROLE,P_TYPE_DOC,P_STATE);
	END IF;
END$$
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- LIST USER CLIENT
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_LST_CLI_PAT;
DELIMITER $$
CREATE PROCEDURE SP_USR_LST_CLI_PAT(
	IN P_TOKEN			VARCHAR(60),	-- TOKEN	
	IN P_NAME 			VARCHAR(100), 	-- NAME
	IN P_NIF  			VARCHAR(15),	-- NIF
	IN P_ID_PAT			INT				-- PATIENT OWNER
)
BEGIN	
	DECLARE P_ID_USR_SSN INT;
	DECLARE P_ID_ROL_SSN INT;
	
	SELECT COUNT(ID_USR) INTO 	P_ID_USR_SSN FROM cfg_acc_sys WHERE ACCESS_TOKEN = P_TOKEN;
	SET P_ID_ROL_SSN 	= 4; -- ROL CLIENT
	IF P_ID_USR_SSN > 0 THEN
		SELECT DISTINCT
		U.ID_USR																					AS ID,
		U.TYP_NIF																					AS ID_TYPE_NIF,
		IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF),'') 						AS TYPE_NIF,
		U.NIF																						AS NIF,
		U.NAMES																						AS NAMES,
		U.EMAIL																						AS EMAIL,
		(SELECT CASE WHEN COUNT(O.ID_USR)>0 THEN 1 ELSE 0 END FROM vet_patient_owners O WHERE
         O.ID_USR = U.ID_USR AND 
         O.ID_PAT = CASE WHEN P_ID_PAT = 0 THEN O.ID_PAT ELSE P_ID_PAT END)							AS OWNER,
		U.SEX 																						AS SEX,
		U.ADDRESS																					AS ADDRESS,
		U.MOVILNUMBER																				AS MOVILNUMBER,
		U.LANDLINE																					AS LANDLINE,
		U.PHOTO																						AS PHOTO
		FROM sys_usr U LEFT JOIN vet_patient_owners V ON U.ID_USR = V.ID_USR
		WHERE 
		U.NAMES			   LIKE CASE WHEN P_NAME IS NULL OR P_NAME = '' THEN U.NAMES ELSE CONCAT('%',P_NAME,'%') END AND 
		U.NIF    		   LIKE CASE WHEN P_NIF  IS NULL OR P_NIF  = '' THEN U.NIF 	ELSE CONCAT('%',P_NIF,'%') END AND 
		U.STATE			=  1 AND
		U.ID_ROL 		=  P_ID_ROL_SSN
		ORDER BY V.ID_USR DESC; 
	END IF;
END$$
DELIMITER ;

-- ---------------------------------------------------------------------------------------------------------------
-- LIST USER CLIENT OWNER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_LST_CLI_PAT_OWN;
DELIMITER $$
CREATE PROCEDURE SP_USR_LST_CLI_PAT_OWN(
	IN P_TOKEN			VARCHAR(60),	-- TOKEN	
	IN P_ID_PAT			INT				-- PATIENT OWNER
)
BEGIN	
	DECLARE P_ID_USR_SSN INT;
	DECLARE P_ID_ROL_SSN INT;
	
	SELECT COUNT(ID_USR) INTO 	P_ID_USR_SSN FROM cfg_acc_sys WHERE ACCESS_TOKEN = P_TOKEN;
	SET P_ID_ROL_SSN 	= 4; -- ROL CLIENT
	IF P_ID_USR_SSN > 0 THEN
		SELECT DISTINCT
		U.ID_USR																					AS ID,
		U.TYP_NIF																					AS ID_TYPE_NIF,
		IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF),'') 						AS TYPE_NIF,
		U.NIF																						AS NIF,
		U.NAMES																						AS NAMES,
		U.EMAIL																						AS EMAIL,
		1							AS OWNER,
		U.SEX 																						AS SEX,
		U.ADDRESS																					AS ADDRESS,
		U.MOVILNUMBER																				AS MOVILNUMBER,
		U.LANDLINE																					AS LANDLINE,
		U.PHOTO																						AS PHOTO
		FROM sys_usr U LEFT JOIN vet_patient_owners V ON U.ID_USR = V.ID_USR
		WHERE 
		V.ID_PAT		=  P_ID_PAT AND
		U.STATE			=  1 AND
		U.ID_ROL 		=  P_ID_ROL_SSN
		ORDER BY V.ID_USR DESC; 
	END IF;
END$$
DELIMITER ;




-- ---------------------------------------------------------------------------------------------------------------
-- LIST USER ADMINISTRADOR SISTEMA
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_ADM_SYS_LST;
DELIMITER $$
CREATE PROCEDURE SP_USR_ADM_SYS_LST(
	IN P_ID  			INT, 			-- ID
	IN P_NAME 			VARCHAR(100), 	-- NAME
	IN P_ROLE  			INT,  			-- ROLE
	IN P_TYPE_DOC 	   	INT,  			-- TIPO NIF
	IN P_STATE      	INT   			-- ESTADO 
)
BEGIN	
	SELECT 
	U.ID_USR																					AS ID,
    U.ID_ROL																					AS ID_ROLE,
	IFNULL((SELECT R.NAME FROM sys_rol R WHERE R.ID_ROL = U.ID_ROL),'') 						AS ROLE,
    U.TYP_NIF																					AS ID_TYPE_NIF,
	IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF),'') 						AS TYPE_NIF,
    U.NIF																						AS NIF,
    U.NAMES																						AS NAMES,
    IFNULL(U.EMAIL,'')																			AS EMAIL,
	U.STATE																						AS ID_STATE,
    IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.STATE),'') 						AS STATE,
    U.SEX 																						AS SEX,
	U.ADDRESS																					AS ADDRESS,
	U.MOVILNUMBER																				AS MOVILNUMBER,
	U.LANDLINE																					AS LANDLINE,
	U.PHOTO																						AS PHOTO
	FROM sys_usr U
	WHERE 
	U.ID_USR		=  CASE WHEN P_ID = 0 THEN U.ID_USR ELSE P_ID END AND
	U.NAMES			   LIKE CASE WHEN P_NAME IS NULL OR P_NAME = '' THEN U.NAMES ELSE CONCAT('%',P_NAME,'%') END AND 
	U.ID_ROL    	=  CASE WHEN P_ROLE  	 = 0 THEN U.ID_ROL  ELSE P_ROLE 	END AND 
	U.TYP_NIF    	=  CASE WHEN P_TYPE_DOC  = 0 THEN U.TYP_NIF ELSE P_TYPE_DOC END AND 
	U.STATE			=  CASE WHEN P_STATE	 = 0 THEN U.STATE	ELSE P_STATE	END	
	ORDER BY U.ID_USR DESC; 
END$$
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- LIST FOR USER MEDICO
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_MED_LST;
DELIMITER $$
CREATE PROCEDURE SP_USR_MED_LST(
	IN P_ID  			INT, 			-- ID
	IN P_NAME 			VARCHAR(100), 	-- NAME
	IN P_ROLE  			INT,  			-- ROLE
	IN P_TYPE_DOC 	   	INT,  			-- TIPO NIF
	IN P_STATE      	INT   			-- ESTADO 
)
BEGIN	
	SELECT 
	U.ID_USR																					AS ID,
    U.ID_ROL																					AS ID_ROLE,
	IFNULL((SELECT R.NAME FROM sys_rol R WHERE R.ID_ROL = U.ID_ROL),'') 						AS ROLE,
    U.TYP_NIF																					AS ID_TYPE_NIF,
	IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF),'') 						AS TYPE_NIF,
    U.NIF																						AS NIF,
    U.NAMES																						AS NAMES,
    IFNULL(U.EMAIL,'')																			AS EMAIL,
	U.STATE																						AS ID_STATE,
    IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.STATE),'') 						AS STATE,
    U.SEX 																						AS SEX,
	U.ADDRESS																					AS ADDRESS,
	U.MOVILNUMBER																				AS MOVILNUMBER,
	U.LANDLINE																					AS LANDLINE,
	U.PHOTO																						AS PHOTO
	FROM sys_usr U
	WHERE 
	-- U.ID_USR_PARENT	=  P_ID AND
	U.NAMES			   LIKE CASE WHEN P_NAME IS NULL OR P_NAME = '' THEN U.NAMES ELSE CONCAT('%',P_NAME,'%') END AND 
	U.ID_ROL    	=  CASE WHEN P_ROLE  	 = 0 THEN U.ID_ROL  ELSE P_ROLE 	END AND 
	U.TYP_NIF    	=  CASE WHEN P_TYPE_DOC  = 0 THEN U.TYP_NIF ELSE P_TYPE_DOC END AND 
	U.STATE			=  CASE WHEN P_STATE	 = 0 THEN U.STATE	ELSE P_STATE	END	AND
	U.ID_ROL IN (2,3,4)
	ORDER BY U.ID_USR DESC; 
END$$
DELIMITER ;

-- ---------------------------------------------------------------------------------------------------------------
-- LIST USER RECEPCIONISTA
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_ADM_LST;
DELIMITER $$
CREATE PROCEDURE SP_USR_ADM_LST(
	IN P_ID  			INT, 			-- ID
	IN P_NAME 			VARCHAR(100), 	-- NAME
	IN P_ROLE  			INT,  			-- ROLE
	IN P_TYPE_DOC 	   	INT,  			-- TIPO NIF
	IN P_STATE      	INT   			-- ESTADO 
)
BEGIN	
	SELECT 
	U.ID_USR																					AS ID,
    U.ID_ROL																					AS ID_ROLE,
	IFNULL((SELECT R.NAME FROM sys_rol R WHERE R.ID_ROL = U.ID_ROL),'') 						AS ROLE,
    U.TYP_NIF																					AS ID_TYPE_NIF,
	IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF),'') 						AS TYPE_NIF,
    U.NIF																						AS NIF,
    U.NAMES																						AS NAMES,
    IFNULL(U.EMAIL,'')																			AS EMAIL,
	U.STATE																						AS ID_STATE,
    IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.STATE),'') 						AS STATE,
	U.SEX 																						AS SEX,
	U.ADDRESS																					AS ADDRESS,
	U.MOVILNUMBER																				AS MOVILNUMBER,
	U.LANDLINE																					AS LANDLINE,
	U.PHOTO																						AS PHOTO
	FROM sys_usr U
	WHERE 
	-- U.ID_USR_PARENT	=  P_ID AND
	U.NAMES			   LIKE CASE WHEN P_NAME IS NULL OR P_NAME = '' THEN U.NAMES ELSE CONCAT('%',P_NAME,'%') END AND 
	U.ID_ROL    	=  CASE WHEN P_ROLE  	 = 0 THEN U.ID_ROL  ELSE P_ROLE 	END AND 
	U.TYP_NIF    	=  CASE WHEN P_TYPE_DOC  = 0 THEN U.TYP_NIF ELSE P_TYPE_DOC END AND 
	U.STATE			=  CASE WHEN P_STATE	 = 0 THEN U.STATE	ELSE P_STATE	END	AND
	U.ID_ROL IN (2,4)
	ORDER BY U.ID_USR DESC; 
END$$
DELIMITER ;

-- ---------------------------------------------------------------------------------------------------------------
-- LIST USER VENDEDOR 
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS  SP_USR_SALESMAN_LST;
DELIMITER $$
CREATE PROCEDURE SP_USR_SALESMAN_LST(
	IN P_ID  			INT, 			-- ID
	IN P_NAME 			VARCHAR(100), 	-- NAME
	IN P_ROLE  			INT,  			-- ROLE
	IN P_TYPE_DOC 	   	INT,  			-- TIPO NIF
	IN P_STATE      	INT   			-- ESTADO 
)
BEGIN	
	SELECT 
	U.ID_USR																					AS ID,
    U.ID_ROL																					AS ID_ROLE,
	IFNULL((SELECT R.NAME FROM sys_rol R WHERE R.ID_ROL = U.ID_ROL),'') 						AS ROLE,
    U.TYP_NIF																					AS ID_TYPE_NIF,
	IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.TYP_NIF),'') 						AS TYPE_NIF,
    U.NIF																						AS NIF,
    U.NAMES																						AS NAMES,
    IFNULL(U.EMAIL,'')																			AS EMAIL,
	U.STATE																						AS ID_STATE,
    IFNULL((SELECT C.VAL_2 FROM cfg_par C WHERE C.ID_PAR = U.STATE),'') 						AS STATE,
    U.SEX 																						AS SEX,
	U.ADDRESS																					AS ADDRESS,
	U.MOVILNUMBER																				AS MOVILNUMBER,
	U.LANDLINE																					AS LANDLINE,
	U.PHOTO																						AS PHOTO
	FROM sys_usr U
	WHERE 
	U.ID_USR		=  P_ID AND
	U.NAMES			   LIKE CASE WHEN P_NAME IS NULL OR P_NAME = '' THEN U.NAMES ELSE CONCAT('%',P_NAME,'%') END AND 
	U.ID_ROL    	=  CASE WHEN P_ROLE  	 = 0 THEN U.ID_ROL  ELSE P_ROLE 	END AND 
	U.TYP_NIF    	=  CASE WHEN P_TYPE_DOC  = 0 THEN U.TYP_NIF ELSE P_TYPE_DOC END AND 
	U.STATE			=  CASE WHEN P_STATE	 = 0 THEN U.STATE	ELSE P_STATE	END	AND
	U.ID_ROL IN (3)
	ORDER BY U.ID_USR DESC; 
END$$
DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------------
-- INSERT USER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_INS;
DELIMITER //
CREATE PROCEDURE SP_USR_INS(
IN  P_TOKEN			VARCHAR(60)		,-- TOKEN USER
IN  P_ROL			INT	 			,-- ROL 
IN  P_NAMES 		VARCHAR(80)	 	,-- NAMES
IN  P_EMAIL			VARCHAR(60)		,-- EMAIL
IN  P_TYP_NIF		INT				,-- TIPO DOCUMENTO 
IN  P_NIF 			VARCHAR(15)		,-- NIF
IN	P_SEX			CHAR(1)			,-- SEX
IN  P_ADDRESS		VARCHAR(250)	,-- ADDRESS
IN  P_MOVILNUMBER	VARCHAR(15)		,-- MOVIL NUMBER
IN  P_LANDLINE		VARCHAR(15)		,-- LINE NUMBER
IN  PIMG 			INT				,-- IF SAVE IMAGE
OUT PIMGOUT 		VARCHAR(60)		,-- IMAGE NAME OUT PARAMETER
OUT PMSG 			VARCHAR(255)	 -- MESSAGE
)
 BEGIN
		DECLARE PARAM_IMG		VARCHAR(60);
		DECLARE PARAM_NUM 		INT;
		DECLARE ID_OK 			INT;
		DECLARE ID_CONT 		INT;
        DECLARE P_STATE 		INT;
		DECLARE P_ID_USR_SSN	INT;
		DECLARE P_ID_ROL_SSN	INT;
		
		SELECT ID_PAR INTO P_STATE FROM cfg_par WHERE val_1 = 'STD' AND VAL_2 = 'Activo';
		SELECT ID_USR INTO P_ID_USR_SSN FROM cfg_acc_sys WHERE ACCESS_TOKEN = P_TOKEN;
		SELECT ID_ROL INTO P_ID_ROL_SSN FROM sys_usr WHERE ID_USR = P_ID_USR_SSN;
	
		IF PIMG = 1 THEN
		SET ID_OK = 0;
		SET ID_CONT = 1;
			WHILE ID_OK = 0 DO
				SELECT COUNT(*)+ID_CONT INTO PARAM_NUM FROM sys_usr;
				SET PARAM_IMG = concat('IMG',LPAD(PARAM_NUM,5,'0'));
				SELECT COUNT(*) INTO PARAM_NUM FROM sys_usr WHERE photo = PARAM_IMG;
				IF PARAM_NUM = 0 THEN
					SET ID_OK = 1;
				ELSE								
					SET ID_CONT = ID_CONT + 1;
				END IF;
			END WHILE;		
		ELSEIF PIMG = 0 THEN
			SELECT VAL_2 INTO PARAM_IMG FROM cfg_par WHERE val_1 = 'IMGU';
		END IF;
			SELECT COUNT(*) INTO @NUM FROM sys_usr WHERE  NAMES = P_NAMES;	
			IF @NUM =0 THEN
				SELECT COUNT(*) INTO @NUM FROM sys_usr WHERE TYP_NIF = P_TYP_NIF AND NIF = P_NIF AND P_NIF <>"";
				IF @NUM = 0 THEN
					INSERT INTO sys_usr(
					ID_ROL,NAMES,EMAIL,
					TYP_NIF,NIF,IDENTIFICATION,
					PASSWORD,SEX,ADDRESS,
					MOVILNUMBER,LANDLINE,PHOTO,
					ID_USR_PARENT,STATE)
					VALUES (
					P_ROL,P_NAMES,P_EMAIL,
					P_TYP_NIF,P_NIF,SHA1(SHA1(P_NIF)),
					SHA1(SHA1(P_NIF)),P_SEX,P_ADDRESS,
					P_MOVILNUMBER,P_LANDLINE,PARAM_IMG,
					P_ID_USR_SSN,P_STATE);		
					SET PIMGOUT = PARAM_IMG;
					SET PMSG = 'Usuario Registrado';
			   ELSE 
					SET PMSG = 'Error, El tipo de identificación y número ya existen, utilice otra o verifique';
			   END IF;
		   ELSE 
				SET PMSG = 'Error, Ya existe un usuario con los nombres o razon social ingresado';
		   END IF;                    
 END//
DELIMITER ;		
-- ---------------------------------------------------------------------------------------------------------------
-- UPDATE USER
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_UPD;
DELIMITER //
CREATE PROCEDURE SP_USR_UPD(
IN  P_TOKEN			VARCHAR(60)		,-- TOKEN USER
IN  P_ID_USR		INT	 			,-- ID USER
IN  P_ROL			INT	 			,-- ROL 
IN  P_NAMES 		VARCHAR(80)	 	,-- NAMES
IN  P_EMAIL			VARCHAR(60)		,-- EMAIL
IN  P_TYP_NIF		INT				,-- TIPO DOCUMENTO 
IN  P_NIF 			VARCHAR(15)		,-- NIF
IN  P_STATE			INT				,-- STATE
IN	P_SEX			CHAR(1)			,-- SEX
IN  P_ADDRESS		VARCHAR(250)	,-- ADDRESS
IN  P_MOVILNUMBER	VARCHAR(15)		,-- MOVIL NUMBER
IN  P_LANDLINE		VARCHAR(15)		,-- LINE NUMBER
IN  PIMG 			INT				,-- IF SAVE IMAGE
OUT PIMGOUT 		VARCHAR(60)		,-- IMAGE NAME OUT PARAMETER
OUT PMSG 			VARCHAR(255)	 -- MESSAGE
)
 BEGIN
		DECLARE PARAM_IMG	VARCHAR(60);
		DECLARE PARAM_NUM 	INT;
		DECLARE ID_OK 		INT;
		DECLARE ID_CONT 	INT;
		DECLARE P_ID_USR_SSN	INT;
		DECLARE P_ID_ROL_SSN	INT;
		
		SELECT ID_USR INTO P_ID_USR_SSN FROM cfg_acc_sys WHERE ACCESS_TOKEN = P_TOKEN;
		SELECT ID_ROL INTO P_ID_ROL_SSN FROM sys_usr WHERE ID_USR = P_ID_USR_SSN;
        
		IF PIMG = 1 THEN
		SET ID_OK = 0;
		SET ID_CONT = 1;
			WHILE ID_OK = 0 DO
				SELECT COUNT(*)+ID_CONT INTO PARAM_NUM FROM sys_usr;
				SET PARAM_IMG = concat('IMG',LPAD(PARAM_NUM,5,'0'));
				SELECT COUNT(*) INTO PARAM_NUM FROM sys_usr WHERE photo = PARAM_IMG;
				IF PARAM_NUM = 0 THEN
					SET ID_OK = 1;
				ELSE								
					SET ID_CONT = ID_CONT + 1;
				END IF;
			END WHILE;		
		ELSEIF PIMG = 0 THEN
			SELECT VAL_2 INTO PARAM_IMG FROM cfg_par WHERE val_1 = 'IMGU';
		END IF;
			SELECT COUNT(*) INTO @NUM FROM sys_usr WHERE  NAMES = P_NAMES AND ID_USR <> P_ID_USR;
			IF @NUM =0 THEN
				SELECT COUNT(*) INTO @NUM FROM sys_usr WHERE TYP_NIF = P_TYP_NIF AND NIF = P_NIF AND P_NIF <>"" AND ID_USR <> P_ID_USR;
				IF @NUM = 0 THEN
					UPDATE sys_usr SET 
						ID_ROL 			= P_ROL,
						NAMES 			= P_NAMES,
						EMAIL			= P_EMAIL,
						TYP_NIF			= P_TYP_NIF,
						NIF				= P_NIF,
						IDENTIFICATION	= SHA1(SHA1(P_NIF)),
						SEX				= P_SEX,
						ADDRESS			= P_ADDRESS,
						MOVILNUMBER 	= P_MOVILNUMBER,
						LANDLINE		= P_LANDLINE,
						PHOTO			= PARAM_IMG,
						ID_USR_PARENT 	= P_ID_USR_SSN, 
						STATE			= P_STATE
						WHERE ID_USR 	= P_ID_USR;		
					SET PIMGOUT 		= PARAM_IMG;
					SET PMSG = 'Usuario Modificado';
			   ELSE 
					SET PMSG = 'Error, El tipo de identificación y número ya existen, utilice otra o verifique';
			   END IF;
		   ELSE 
				SET PMSG = 'Error, Ya existe un usuario con los nombres o razon social ingresado';
		   END IF;                    
 END//
DELIMITER ;		
-- ---------------------------------------------------------------------------------------------------------------
-- UPDATE CREDENTIAL
-- ---------------------------------------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS SP_USR_UPD_PWD;
DELIMITER //
CREATE PROCEDURE SP_USR_UPD_PWD(
IN P_ID_USR				INT,			-- ID_USR		
IN P_PASSWORD			VARCHAR(60),	-- IDENTIFICACION NUEVA
OUT PMSG				VARCHAR(255)	
)
BEGIN
	SELECT COUNT(*) INTO @NUM FROM sys_usr WHERE ID_USR = P_ID_USR;
	IF @NUM = 1 THEN
		UPDATE sys_usr SET  PASSWORD = P_PASSWORD WHERE ID_USR = P_ID_USR;
		SET PMSG = 'Sus credenciales han sido modificas';
	ELSE
		SET PMSG = 'Error, usuario no valido';
	END IF;
END//
DELIMITER ;



		
		