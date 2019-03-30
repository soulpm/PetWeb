use maskotaweb_db;
-- ---------------------------------------------- 
-- LISTA MENU DEL SISTEMA SEGUN ROL INGRESADO
-- ----------------------------------------------
DROP PROCEDURE IF EXISTS SP_MNU_LST_CFG;
DELIMITER $$
CREATE PROCEDURE SP_MNU_LST_CFG(
	IN ROLE INT 	-- ROL DE USUARIO
)
BEGIN	
	SELECT 
	m.id_mnu 		id,
	m.name			name,
	m.url			url,
	m.icon 			icon
	FROM cfg_mnu m, sys_mnu_rol mr
	where 
	m.id_mnu = mr.id_mnu and 
	mr.id_rol = ROLE;
END$$
DELIMITER ;



DELIMITER $$
CREATE FUNCTION FN_IS_USER_AUTHORIZED (p_token VARCHAR(255))
RETURNS INT DETERMINISTIC
BEGIN
DECLARE NUM_VALID INT; 
SELECT COUNT(ID_USR) INTO NUM_VALID FROM cfg_acc_sys WHERE ACCESS_TOKEN = p_token;
RETURN NUM_VALID;
END$$
DELIMITER ;
