drop database maskotaweb_db;
create database maskotaweb_db;
use maskotaweb_db;
alter database maskotaweb_db default character set utf8;
alter database maskotaweb_db default collate utf8_unicode_ci;

drop table if exists cfg_par;

/*==============================================================*/
/* Table: CFG_PAR                                               */
/*==============================================================*/
create table cfg_par
(
   ID_PAR               int not null auto_increment comment 'Identificacion del Registro',
   VAL_1                varchar(25) comment 'Valor 1  del parametro',
   VAL_2                varchar(100) comment 'Valor 2  del parametro',
   VAL_3                varchar(100) comment 'Valor 3 del parametro',
   VAL_4                varchar(100) comment 'Valor 4 del parametro',
   DESCRIPTION          varchar(200) comment 'Descripcion del parametro',
   primary key (ID_PAR)
);

alter table cfg_par comment 'Parametros Generales del Sistema';

INSERT INTO cfg_par (val_1,val_2,val_3,val_4,description)
VALUES ('STD','Activo','Estado Activo','','Estado de un registro del sistema');
INSERT INTO cfg_par (val_1,val_2,val_3,val_4,description)
VALUES ('STD','Bloqueado','Estado Bloqueado','','Estado de un registro del sistema');
INSERT INTO cfg_par (val_1,val_2,val_3,val_4,description)
VALUES ('TNIF','DNI','Documento Nacional de Identidad','','Tipo Documento');
INSERT INTO cfg_par (val_1,val_2,val_3,val_4,description)
VALUES ('TNIF','RUC','Registro Unico del Contribuyente','','Tipo Documento');
INSERT INTO cfg_par (val_1,val_2,val_3,val_4,description)
		VALUES ('IMGU','user-image-default.jpg','','','Imagen Usuario default');


drop table if exists cfg_mnu;

/*==============================================================*/
/* Table: CFG_MNU                                               */
/*==============================================================*/
create table cfg_mnu
(
   ID_MNU               int not null auto_increment comment 'Identificación del Registro',
   NAME                 varchar(100) comment 'Nombre del Menu',
   URL                  varchar(100) comment 'Direccion Url del Menu',
   DESCRIPTION          varchar(200) comment 'Detalle o descripcion del registro',
   ICON                 varchar(60) comment 'Icono del Menu bajo Font Awesome 4',
   primary key (ID_MNU)
);

alter table cfg_mnu comment 'Menus del Sistema';

INSERT INTO cfg_mnu (NAME,URL,DESCRIPTION,ICON)
VALUES('Inicio','/inicio','Pagina Inicial del Sistema','fa fa-home');
INSERT INTO cfg_mnu (NAME,URL,DESCRIPTION,ICON)
VALUES('Usuarios','/users','Pagina de Usuarios','fa fa-address-book');
INSERT INTO cfg_mnu (NAME,URL,DESCRIPTION,ICON)
VALUES('Pacientes','/patient','Pagina de Pacientes','fa fa-file-text-o');
INSERT INTO cfg_mnu (NAME,URL,DESCRIPTION,ICON)
VALUES('Reportes','/reports','Pagina de Reportes','fa fa-bar-chart');

		

drop table if exists sys_rol;

/*==============================================================*/
/* Table: SYS_ROL                                               */
/*==============================================================*/
create table sys_rol
(
   ID_ROL               int not null auto_increment comment 'Identificacion del Registro',
   NAME                 varchar(55) comment 'Nombre del Registro',
   STATE                int comment 'Estado del Registro',
   primary key (ID_ROL)
);

alter table sys_rol comment 'Roles del Sistema';

INSERT INTO sys_rol (name,state) VALUES ('Administrador',1);
INSERT INTO sys_rol (name,state) VALUES ('Médico',1);
INSERT INTO sys_rol (name,state) VALUES ('Recepcionista',1);
INSERT INTO sys_rol (name,state) VALUES ('Cliente',1);


/*==============================================================*/
/* Index: IDX_ROL_NAME                                          */
/*==============================================================*/
create index IDX_ROL_NAME on sys_rol
(
   NAME
);

alter table sys_rol add constraint FK_SYS_ROL_STATE foreign key (STATE)
      references cfg_par (ID_PAR) on delete restrict on update restrict;

		
drop table if exists sys_usr;

/*==============================================================*/
/* Table: SYS_USR                                               */
/*==============================================================*/
create table sys_usr
(
   ID_USR               int not null auto_increment comment 'Identificador del Registro',
   ID_ROL               int comment 'Identificador Rol Usuario',
   ID_USR_PARENT        int comment 'Usuario Padre Relacionado',
   NAMES                varchar(100) comment 'Nombres del Usuario',
   EMAIL                varchar(60) comment 'Correo Electronico',
   TYP_NIF              int comment 'Tipo Documento',
   NIF                  varchar(15) comment 'Numero Documento',
   IDENTIFICATION       varchar(60) comment 'Identificacion Sistema',
   PASSWORD             varchar(60) comment 'Clave del Sistema',
   SEX					char(1)	comment 'Genero Sexual del Usuario',
   ADDRESS				VARCHAR(250) comment 'Direccion Fisica del Usuario' DEFAULT '',
   MOVILNUMBER			VARCHAR(15)	comment 'Numero Movil del Usuario' DEFAULT '',
   LANDLINE				VARCHAR(15)	comment 'Numero Fijo del Usuario' DEFAULT '',
   PHOTO                varchar(45) comment 'Foto',
   STATE                int comment 'Estado Usuario',
   primary key (ID_USR)
);

alter table sys_usr comment 'Usuarios del Sistema';

INSERT INTO sys_usr(id_rol,names,email,photo,typ_nif,nif,identification,password,state)
VALUES (1,'Saúl José Pérez Mozombite','sauljose1301@gmail.com','user-image-default.jpg',3,'45108574',sha1(sha1('saul')),sha1(sha1('saul')),1);
INSERT INTO sys_usr(id_rol,names,email,photo,typ_nif,nif,identification,password,state)
VALUES (1,'Daniel Ramirez',' ','user-image-default.jpg',3,' ',sha1(sha1('daniel')),sha1(sha1('daniel')),1);

INSERT INTO sys_usr(id_rol,names,email,photo,typ_nif,nif,identification,password,state)
VALUES (2,'Medico 1','medico1@gmail.com','user-image-default.jpg',3,'12345678',sha1(sha1('12345678')),sha1(sha1('12345678')),1);

INSERT INTO sys_usr(id_rol,names,email,photo,typ_nif,nif,identification,password,state)
VALUES (3,'Recepcionista 1','recepcionista1@gmail.com','user-image-default.jpg',3,'78945612',sha1(sha1('78945612')),sha1(sha1('78945612')),1);

alter table sys_usr add constraint FK_SYS_STATE foreign key (STATE)
      references cfg_par (ID_PAR) on delete restrict on update restrict;

alter table sys_usr add constraint FK_SYS_USR_ROL foreign key (ID_ROL)
      references sys_rol (ID_ROL) on delete restrict on update restrict;
 


drop table if exists cfg_acc_sys;

/*==============================================================*/
/* Table: CFG_ACC_SYS                                           */
/*==============================================================*/
create table cfg_acc_sys
(
   ACCESS_TOKEN         VARCHAR(255) not null comment 'Valor Token de Acceso',
   ID_USR               int not null comment 'Identificador de Usuario ',
   EXPIRES              timestamp comment 'Tiempo Expiracion del Token',
   SCOPE                VARCHAR(250) comment 'Ambito del Token',
   primary key (ID_USR)
);

alter table cfg_acc_sys comment 'Autorizacion de Acceso al Sistema';

alter table cfg_acc_sys add constraint FK_SYS_ACCESS foreign key (ID_USR)
      references sys_usr (ID_USR) on delete restrict on update restrict;

	  
	  drop table if exists sys_mnu_rol;

/*==============================================================*/
/* Table: SYS_MNU_ROL                                           */
/*==============================================================*/
create table sys_mnu_rol
(
   ID_MNU_ROL           int not null auto_increment comment 'Identificacion del Registro',
   ID_MNU               int comment 'Identificador del Menu del Sistema',
   ID_ROL               int comment 'Identificador del Rol del Sistema',
   primary key (ID_MNU_ROL)
);

alter table sys_mnu_rol comment 'Menus por Roles del Sistema';

-- MENU ADMINISTRADOR
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (1,1);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (2,1);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (3,1);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (4,1);
-- MENU MEDICO
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (1,2);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (2,2);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (3,2);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (4,2);
-- MENU RECEPCIONISTA
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (1,3);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (2,3);
INSERT INTO sys_mnu_rol (id_mnu,id_rol)
VALUES (3,3);

alter table sys_mnu_rol add constraint FK_SYS_MNU_ID_MNU foreign key (ID_MNU)
      references cfg_mnu (ID_MNU) on delete restrict on update restrict;

alter table sys_mnu_rol add constraint FK_SYS_MNU_ID_ROL foreign key (ID_ROL)
      references sys_rol (ID_ROL) on delete restrict on update restrict;


drop table if exists vet_kind_patient;

/*==============================================================*/
/* Table: VET_KIND_PATIENT                                      */
/*==============================================================*/
create table vet_kind_patient
(
   ID_KIND_PAT          int not null auto_increment comment 'Identificador Tipo Paciente',
   NAME                 varchar(85) comment 'Nombre Tipo Paciente',
   primary key (ID_KIND_PAT)
);

alter table vet_kind_patient comment 'Tipo Paciente';

INSERT INTO vet_kind_patient(NAME)
VALUES('Perro'),('Gato');
	  
	  
drop table if exists vet_patient;

/*==============================================================*/
/* Table: VET_PATIENT                                           */
/*==============================================================*/
create table vet_patient
(
   ID_PAT               int not null auto_increment comment 'Identificador Paciente Registrado',
   ID_KIND_PAT          int comment 'Identificador Tipo Paciente',
   NAME                 varchar(85) comment 'Nombre Paciente',
   SEX                  char(1) comment 'Sexo',
   BREED                varchar(85) comment 'Raza',
   COLOR                varchar(40) comment 'Color de Pelo',
   DATE_BORN            date comment 'Fecha Nacimiento',
   YEAR                 INT comment 'Edad expresada en años',
   MONTH                INT comment 'Edad expresada en meses',
   primary key (ID_PAT)
);

alter table vet_patient comment 'Pacientes';

INSERT INTO vet_patient
(name,sex,breed,color,id_kind_pat,date_born,year,month)
VALUES ('Federico',1,'Mestizo','Negro',1,null,1,2);

alter table vet_patient add constraint FK_REFERENCE_11 foreign key (ID_KIND_PAT)
      references vet_kind_patient (ID_KIND_PAT) on delete restrict on update restrict;


drop table if exists vet_clinic_signs;

/*==============================================================*/
/* Table: VET_CLINIC_SIGNS                                      */
/*==============================================================*/
create table vet_clinic_signs
(
   ID_CLIN_SGN          int not null comment 'Identificador Registro Signo Clinico',
   VALUE                varchar(250) comment 'Valor o Nombre del Signo Clinico',
   TYPE_1				int comment 'Clasificacion Signos Clinicos',
   TYPE_2				INT comment 'Sub Clasificacion Signos Clinicos',
   primary key (ID_CLIN_SGN)
);

alter table vet_clinic_signs comment 'Signos Clinicos del Paciente';

INSERT INTO vet_clinic_signs (ID_CLIN_SGN,value,type_1,type_2)
VALUES 
(1,'Anorexia',1,0),
(2,'Legaña',1,0),
(3,'Dificultad Respiratoria',1,0),
(4,'Diarrea',1,0),
(5,'Hiperqueratosis',1,0),
(6,'Ascitis',1,0),
(7,'Diarrea c/Sangre',1,0),
(8,'Deshidratación',1,0),
(9,'Dermatitis',1,0),
(10,'Tenesmo',1,0),
(11,'Hictericia',1,0),
(12,'Lesiones de la Piel',1,0),
(13,'Vómitos',1,0),
(14,'Mucosas Pálidas',1,0),
(15,'Cojera',1,0),
(16,'Vómitos c/Sangre',1,0),
(17,'Convulsiones',1,0),
(18,'Excema Humedo',1,0),
(19,'Rejurgitación',1,0),
(20,'Tumoraciones',1,0),
(21,'Parasitosis Int',1,0),
(22,'Secreción Nasal',1,0),
(23,'Hemorragia',1,0),
(24,'Parasitosis Externas',1,0),
(25,'Tos',1,0),
(26,'Fractura',1,0),
(27,'Estornudos',1,0),

(28,'Diagnostico 1',2,28),
(29,'Diagnostico 2',2,29),
(30,'Diagnostico 3',2,30),
(31,'Diagnostico 4',2,31),

(32,'Tratamiento 1',3,28),
(33,'Tratamiento 2',3,28),
(34,'Tratamiento 3',3,30),
(35,'Tratamiento 4',3,31),

(36,'Puppy DP',4,0),
(37,'Cuadruple',4,0),
(38,'Quintuple',4,0),
(39,'Sextuple',4,0),
(40,'Sextuple + R',4,0),
(41,'Rabia',4,0),
(42,'Triple Felina',4,0)
;


drop table if exists vet_patient_owners;

/*==============================================================*/
/* Table: VET_PATIENT_OWNERS                                    */
/*==============================================================*/
create table vet_patient_owners
(
   ID_PAT_OWN           int not null auto_increment comment 'Identificador del Registro',
   ID_USR               int comment 'Identificador del Usuario',
   ID_PAT               int comment 'Identificador Paciente Registrado',
   primary key (ID_PAT_OWN)
);

alter table vet_patient_owners comment 'Propietario de Paciente';

alter table vet_patient_owners add constraint FK_PACIENTE_PROPIETARIO foreign key (ID_PAT)
      references vet_patient (ID_PAT) on delete restrict on update restrict;

alter table vet_patient_owners add constraint FK_RELACION_PROPIETARIO_USUARIO foreign key (ID_USR)
      references sys_usr (ID_USR) on delete restrict on update restrict;

drop table if exists vet_history_clinic;

/*==============================================================*/
/* Table: VET_HISTORY_CLINIC                                    */
/*==============================================================*/
create table vet_history_clinic
(
   ID_HIS               int not null auto_increment comment 'Identificador del Registro',
   ID_PAT               int comment 'Identificador Paciente Registrado',
   ID_CLIN_SGN          varchar(250) comment 'Identificadores Registros Signos Clinicos separados por ;',
   ID_USR_MED           int comment 'Identificador del Registro Medico',
   DATE_REG             date comment 'Fecha Registro Historia',
   STATURE              float comment 'Estatura',
   WEIGHT               float comment 'Peso',
   DIAGNOSTIC           varchar(250) comment 'Diagnostico',
   TREATMENT            varchar(250) comment 'Tratamiento',
   NEXT_DATE            date comment 'Proxima Cita',
   VACCINE              varchar(250) comment 'Vacunas',
   TEMPERATURE			VARCHAR(100) comment 'Temperatura',
   RECOMMEND			VARCHAR(250) comment 'Recomendaciones de la atencion',
   CHEMOTHERAPY         varchar(250) comment 'Quimioterapia',
   PAYMENT              float comment 'Pago',
   VACCINE_COMPLETED	INT	comment 'Indicador Vacunacion Completa',
   DESPARACITADO		INT comment 'Indicador Desparacitado',
   WITH_OPERATION		INT comment 'Indicador Ya ha tenido Operaciones',
   SOCIAL_KNOWN			INT comment 'Indicador Relacion Referencia',
   IS_PULGAS			INT comment 'Indicador Tiene Pulgas',
   IVE_GARRAPATA		INT comment 'Indicador Tiene Garrapata',
   IVE_HONGOS			INT comment 'Indicador Tiene Hongos',	
   IVE_OTITIS			INT comment 'Indicador Tiene Otitis',	
   IVE_BANIO_STD		INT comment 'Indicador Tiene Banio Estandar',
   IVE_BANIO_MED		INT comment 'Indicador Tiene Banio Medicado',
   IVE_CORTE			INT comment 'Indicador Tiene Corte',
   IVE_PROMO_SUCCESS	INT comment 'Indicador Hizo Promocion Gratis',
   -- STATE				INT comment 'Estado Historia Clinica' default 1
   primary key (ID_HIS)
);

alter table vet_history_clinic comment 'Registros de Historia Clinica';

alter table vet_history_clinic add constraint FK_HISTORIA_PACIENTE foreign key (ID_PAT)
      references vet_patient (ID_PAT) on delete restrict on update restrict;

/*alter table vet_history_clinic add constraint FK_HISTORIA_SIGNO_CLINICO foreign key (ID_CLIN_SGN)
      references vet_clinic_signs (ID_CLIN_SGN) on delete restrict on update restrict;*/

alter table vet_history_clinic add constraint FK_REFERENCE_12 foreign key (ID_USR_MED)
      references sys_usr (ID_USR) on delete restrict on update restrict;
	  
	  