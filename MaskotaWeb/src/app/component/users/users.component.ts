import { Component, OnInit, ViewChild } from '@angular/core';
import * as $ from 'jquery';
import { environment } from 'src/environments/environment';
import { IRoleModel, ISexModel } from 'src/app/model/login.model';
import { UserService } from 'src/app/services/user.service';
import { IDocumentModel, IStateModel, IUserModel } from 'src/app/model/user.model';
declare var $:$;

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.css']
})
export class UsersComponent implements OnInit {
  
  titlePage:string                        = "Usuarios";
  root_path_image:string                  = environment.pathImage;
  filterIdUser:number                     = 0;
  @ViewChild('dtUsers') dataTable: any;
  filterName:string                       = "";
  filterRole:IRoleModel                   = { idRole:0};
  filterTypeDoc:IDocumentModel            = { idDocument:0};
  filterState:IStateModel                 = { idState:0};
  listRoles:IRoleModel[]                  = [];
  listTypeDocument:IDocumentModel[]       = [];
  listState:IStateModel[]                 = [];
  modalRegisterName:string                = "modalUser";
  modalCredentialName:string              = "modalUserCredential";     
  modalAsignServerName:string             = "modalAsignServerUser";
  modalAssignSalesMan:string              = "modalAssignClient";
  listUsers:IUserModel[]                  = [];
  inputPassword:string                    = "";
  inputConfirmPassword:string             = "";
  userEditParent:IUserModel               = {role:{},typeNif:{},estate:{}};
  isEditMode:boolean                      = false;
  titleModal:string;
  listSexInput:ISexModel[]  = [];    
  parentComponent:UsersComponent;
  tokenUserSession:string                 = "";
  constructor(private userService:UserService) 
  { }
  ngOnInit() { 
      let obj  = this;
      obj.tokenUserSession = sessionStorage.getItem("user_session");
      obj.configListItems();
      obj.parentComponent = this;
      obj.loadRoles();
      obj.getListUser();
      
  }

  configListItems():void{
      let obj = this;
      obj.listTypeDocument = [
        {idDocument:0,name:"--Todos--"},
        {idDocument:3,name:"DNI"},
        {idDocument:4,name:"RUC"}];
      
      obj.listState = [
      {idState:0,name:"--Todos--"},
      {idState:1,name:"Activo"},
      {idState:2,name:"Bloqueado"}
      ];

      obj.listSexInput = [
          {idRegister:"M",name:"Masculino"},
          {idRegister:"F",name:"Femenino"}
      ];

      obj.filterTypeDoc = obj.listTypeDocument[0];
      obj.filterState   = obj.listState[0];
  }
  loadRoles():void{
      let obj = this;
      obj.userService.getListRole(obj.tokenUserSession).then(
      (val) => {
        let rolesServerList:IRoleModel[] = val;
        //console.log("roles: "+JSON.stringify(rolesServerList));
        obj.listRoles.push({idRole:0,name:"--Todos--"});
        for(let j=0;j<rolesServerList.length;j++){
           obj.listRoles.push(rolesServerList[j]);
        }
        obj.filterRole    =  obj.listRoles[0];
      } , 
      (err) => {
        
      });
  }
  newUser():void{
    let obj = this;
    obj.isEditMode = false;
    obj.titleModal = "Registro Usuario";
    obj.userEditParent = {
      role:{idRole:1},typeNif:{idDocument:3},estate:{idState:3},
      sex:obj.listSexInput[0].idRegister,
      movilNumber:"",address:"",landLine:"",email:""};
    $("#"+obj.modalRegisterName).modal('show');
    $("#"+obj.modalRegisterName).on('shown.bs.modal', function() {
      $("#inputName").focus();
    });
  }
  editUser(userEdit:IUserModel):void{
      let obj = this;
      obj.isEditMode = true;
      obj.titleModal = "Editar Usuario";
      obj.userEditParent = userEdit;  
      $("#"+obj.modalRegisterName).modal('show');    
      $("#"+obj.modalRegisterName).on('shown.bs.modal', function() {
        $("#inputName").select();
      });
    }

  showModalCredential(userEdit:IUserModel):void{
    let obj = this;
    obj.isEditMode = false;
    obj.titleModal = "Credenciales de Usuario";
    obj.userEditParent = userEdit;
    obj.inputPassword = "";
    obj.inputConfirmPassword = "";
    $("#"+obj.modalCredentialName).modal('show');
    
  }
  getListUser():void{
      let obj = this;
      obj.listUsers = [];
      obj.dataTable.reset();
      let entity:IUserModel = 
      { userId:0,tokenSession:obj.tokenUserSession,names:obj.filterName,role:obj.filterRole,typeNif:obj.filterTypeDoc,estate:this.filterState};
      this.userService.getListUsers(entity).then(
      (val) => {
        obj.listUsers = val;
      } , 
      (err) => {
        
      });
  }
  
}

