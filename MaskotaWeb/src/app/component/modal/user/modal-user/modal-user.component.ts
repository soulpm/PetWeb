import { Component, OnInit, Inject,Input } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { environment } from 'src/environments/environment';
import { UsersComponent } from '../../../users/users.component';
import * as $ from 'jquery';
import { IDocumentModel, IStateModel, IUserModel } from 'src/app/model/user.model';
import { IRoleModel, ISexModel } from 'src/app/model/login.model';
declare var $:$;

@Component({
  selector: 'app-modal-user',
  templateUrl: './modal-user.component.html',
  styleUrls: ['./modal-user.component.css']
})
export class ModalUserComponent implements OnInit {

  root_path_image:string                  = environment.pathImage;
  image_user_name:string                  = "user-image-default.jpg";
  showMessageModal:boolean                = false;
  titleMessageModal:string                = "";
  messageModal:string                     = "";
  typeAlertModal:string                   = "alert-danger";
  modalRegisterName:string                = "modalUser";
  listRolesInput:IRoleModel[]             = [];
  listSexInput:ISexModel[]                = [];
  listTypeDocumentInput:IDocumentModel[]  = [];
  listStateInput:IStateModel[]            = [];
  tokenUserSession:string                 = "";
  @Input('parentObj') parent:any;
  constructor(private userService:UserService,
  @Inject(UsersComponent) private userComponent:UsersComponent) 
  { 
    
  }

  ngOnInit() { 
    let obj = this;
    obj.tokenUserSession = sessionStorage.getItem("user_session");
    //this.configModal();
     obj.loadSelectors();    
    
  }

  configModal():void{
    let obj = this;
  }
  
  loadSelectors():void{
      this.loadRoles();

      this.listTypeDocumentInput = [
        {idDocument:3,name:"DNI"},
        {idDocument:4,name:"RUC"}];
      this.parent.userEditParent.typeNif = this.listTypeDocumentInput[0];  
      this.listStateInput = [
        {idState:1,name:"Activo"},
        {idState:2,name:"Bloqueado"},
      ];  
      
  }
 loadRoles():void{
   let obj = this;
   obj.userService.getListRole(obj.tokenUserSession).then(
    (val) => {
      obj.listRolesInput = val;
      obj.parent.userEditParent.role = val[0];
    } , 
    (err) => {
      
    });
 }
 saveUser():void{
      if(this.validateFields()){
          if(this.parent.isEditMode){
            this.modifyUserDB();
          }
          else{
            this.createUserDB();
          }  
      }
  }
  
  createUserDB():void{
    let obj = this;
    console.log("usuario edit parent: "+JSON.stringify(obj.parent.userEditParent));
    let entity:IUserModel = 
    { 
      tokenSession:obj.tokenUserSession,
      names       :obj.parent.userEditParent.names,
      role        :obj.parent.userEditParent.role,
      typeNif     :obj.parent.userEditParent.typeNif,
      email       :((obj.parent.userEditParent.email==null)?"":obj.parent.userEditParent.email),
      numberNif   :obj.parent.userEditParent.numberNif,
      sex         :obj.parent.userEditParent.sex,
      address     :obj.parent.userEditParent.address,
      movilNumber :obj.parent.userEditParent.movilNumber,
      landLine    :obj.parent.userEditParent.landLine
    };
    this.userService.createUser(entity,0,0).then(
      (val) => {
            let message:string =  val;
            if(message.indexOf("Error")!=-1)
            {obj.showAlertMessage(message,"alert-danger");}
            else{ 
              obj.showAlertMessage(message,"alert-success");
              obj.userComponent.getListUser();
              setTimeout(function(){
                $("#"+obj.modalRegisterName).modal('hide');
              },2000);
            }
      } ,
      (err) => {
          let message =  "Ocurrio un error, "+err;
          this.showAlertMessage(message,"alert-danger");
      });
  }
  modifyUserDB():void{
    let obj = this;
    let entity:IUserModel = 
    { 
      tokenSession  :obj.tokenUserSession,
      userId        :obj.parent.userEditParent.userId,
      names         :obj.parent.userEditParent.names,
      role          :obj.parent.userEditParent.role,
      typeNif       :obj.parent.userEditParent.typeNif,
      email         :obj.parent.userEditParent.email,
      numberNif     :obj.parent.userEditParent.numberNif,
      sex           :obj.parent.userEditParent.sex,
      address       :obj.parent.userEditParent.address,
      movilNumber   :obj.parent.userEditParent.movilNumber,
      landLine      :obj.parent.userEditParent.landLine,
      estate        :obj.parent.userEditParent.estate
    };
    this.userService.editUser(entity,0).then(
      (val) => {
            let message:string =  val;
            if(message.indexOf("Error")!=-1)
            {obj.showAlertMessage(message,"alert-danger");}
            else{ 
              obj.showAlertMessage(message,"alert-success");
              obj.userComponent.getListUser();
              setTimeout(function(){
                $("#"+obj.modalRegisterName).modal('hide');
              },2000);
            }
      } ,
      (err) => {
          let message =  "Ocurrio un error, "+err;
          this.showAlertMessage(message,"alert-danger");
      });
  }

  getSelectTypeDoc(valueSelected:IDocumentModel):IDocumentModel{
      let valueReturn:IDocumentModel = null;
      for(let k=0;k<this.listTypeDocumentInput.length;k++){
          if(valueSelected.idDocument ==  this.listTypeDocumentInput[k].idDocument){
             valueReturn = this.listTypeDocumentInput[k];
             break;
          }
      }
      return valueReturn;
  }
  loadImage():void{
    document.getElementById("file-image-user").click();
  }
  validateFields():boolean
  {  
     let obj = this;
     let value:boolean = true;
     let message:string = "Debe completar los campos: ";
     if(obj.parent.userEditParent.role.idRole == 0){
        message  += "Perfil, ";
        value = false;
     } 
     if(obj.parent.userEditParent.names == null)
     {
      message  += "Nombres, ";
      value = false;
     }
     if(obj.parent.userEditParent.typeNif.idDocument==0){
        message  += "Tipo Documento, ";
        value = false;
     } 
     if(obj.parent.userEditParent.numberNif==null){
        message  += "Nro Documento, ";
        value = false;
     }
     if(obj.parent.userEditParent.address     == "" && 
        obj.parent.userEditParent.movilNumber == "" && 
        obj.parent.userEditParent.landLine == ""){
          message += "Dirección, celular o teléfono fijo, ";
          value = false;
     }

     if(!value){
        message = message.substr(0,message.length-2);
        this.showAlertMessage(message,"alert-danger");
     }
     return value;
  }
  showAlertMessage(message:string,typeAlert:string):void{
    this.showMessageModal = true;
    this.typeAlertModal = typeAlert;
    this.titleMessageModal = "Mensaje Sistema:";
    this.messageModal  = message;
    let obj = this;
    setTimeout(function(){
        obj.showMessageModal=false; 
    },3500);
  }

}
