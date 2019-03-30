import { Component, OnInit, Inject,Input } from '@angular/core';
import { environment } from 'src/environments/environment';
import { IDocumentModel, IStateModel, IUserModel } from 'src/app/model/user.model';
import { IRoleModel, ISexModel } from 'src/app/model/login.model';
import { PatientService } from 'src/app/services/patient.service';
import { IPatientModel, ISexPatientModel } from 'src/app/model/patient.model';
import { IMessageModel } from 'src/app/model/config.model';
import { toString,toInteger } from '../../../../utilities/utility';
import * as $ from 'jquery';
import { UserService } from 'src/app/services/user.service';
declare var $:$;

@Component({
  selector: 'app-modal-patient',
  templateUrl: './modal-patient.component.html',
  styleUrls: ['./modal-patient.component.css']
})
export class ModalPatientComponent implements OnInit {

  root_path_image:string                  = environment.pathImage;
  image_user_name:string                  = "user-image-default.jpg";
  listSexInput:ISexModel[]                = [];
  messageModal:IMessageModel              = {};
  listRolesInput:IRoleModel[]             = [];
  listTypeDocumentInput:IDocumentModel[]  = [];
  listPatientSex:ISexPatientModel[]       = [];
  listStateInput:IStateModel[]            = [];
  tokenUserSession:string                 = "";
  @Input('parentObj') parent:any;
  
  constructor(private patientService:PatientService,private userService:UserService) 
  { 
    
  }
  ngOnInit() { 
    let obj = this;
    obj.tokenUserSession = sessionStorage.getItem("user_session");
    //this.configModal();
     obj.loadSelectors();    

  }
  verifyDatePatient():void{
    let obj = this;
    let dateCurrent:Date = new Date();
    if(obj.parent.entityEditParent.year<=40 && obj.parent.entityEditParent.year>=0)
    {
        dateCurrent.setFullYear(dateCurrent.getFullYear()-obj.parent.entityEditParent.year);   
    }
    else{ obj.parent.entityEditParent.year = "0"; return; }

    if(obj.parent.entityEditParent.month<12 &&obj.parent.entityEditParent.month>=0){
        dateCurrent.setMonth(dateCurrent.getMonth()-obj.parent.entityEditParent.month); 
    }
    else{ obj.parent.entityEditParent.month ="0" ; return;}
    obj.parent.inputDateBorn = {
        year: dateCurrent.getFullYear(),
        month: dateCurrent.getMonth()+1,
        day: dateCurrent.getDate()
    }; 
  }
  configModal():void{
    let obj = this;
  }
  loadSelectors():void{
     let obj = this;
      obj.listSexInput = [
          {idRegister:"M",name:"Masculino"},
          {idRegister:"F",name:"Femenino"}
      ];
      
      obj.listTypeDocumentInput = [
        {idDocument:3,name:"DNI"},
        {idDocument:4,name:"RUC"}];
      obj.listPatientSex = [
        {idSex:1,name:"Macho"},
        {idSex:2,name:"Hembra"}
      ];
  }
  save():void{
      if(this.validateFields()){
          if(this.parent.isEditMode){
            this.modifyDB();
          }
          else{
            this.createDB();
          } 
      }
  }
  
  createDB():void{
    let obj = this;
    let tokenSession = sessionStorage.getItem("user_session");
    let dateBornData:string = "";
    if(obj.parent.inputDateBorn!=null){
      let db = obj.parent.inputDateBorn;
      dateBornData = ""+db.year+"-"+((db.month<10)?"0"+db.month:db.month)+"-"+((db.day<10)?"0"+db.day:db.day);
    
    }
    let entity:IPatientModel = 
    { 
      names       :obj.parent.entityEditParent.names,
      breed       :toString(obj.parent.entityEditParent.breed),
      kindPatient :obj.parent.entityEditParent.kindPatient,
      year        :obj.parent.entityEditParent.year,
      month       :obj.parent.entityEditParent.month,
      dateBorn    :dateBornData,
      color       :toString(obj.parent.entityEditParent.color),
      sex         :obj.parent.entityEditParent.sex
    };
    obj.patientService.createPatient(entity,tokenSession).then(
      (val) => {
            let message:any =  val;
            if(message.indexOf("Error")!=-1)
            {
              obj.showAlertMessage(message,"alert-danger");
            }
            else{ 
              obj.parent.loadListPatients();
              if(obj.parent.userOwner.names!=""){
                obj.createPatientUserDB();
              }
              else{ 
                obj.showAlertMessage(message,"alert-success");
                setTimeout(function(){
                  $("#"+obj.parent.modalRegisterName).modal('hide');
                },3000);
              }
          }
      } ,
      (err) => {
          let message =  "Ocurrio un error, "+err;
          this.showAlertMessage(message,"alert-danger");
      });
  }
  modifyDB():void{
    let obj = this;
    let tokenSession = sessionStorage.getItem("user_session");
    let dateBornData:string = "";
    if(obj.parent.inputDateBorn!=null){
      let db = obj.parent.inputDateBorn;
      dateBornData = ""+db.year+"-"+((db.month<10)?"0"+db.month:db.month)+"-"+((db.day<10)?"0"+db.day:db.day);
    
    }
    let entity:IPatientModel = 
    { 
      idPatient   :obj.parent.entityEditParent.idPatient,
      names       :obj.parent.entityEditParent.names,
      breed       :toString(obj.parent.entityEditParent.breed),
      kindPatient :obj.parent.entityEditParent.kindPatient,
      year        :obj.parent.entityEditParent.year,
      month       :obj.parent.entityEditParent.month,
      dateBorn    :dateBornData,
      color       :toString(obj.parent.entityEditParent.color),
      sex         :obj.parent.entityEditParent.sex
    };
    obj.patientService.editPatient(entity,tokenSession).then(
      (val) => {
            let message:any =  val;
            if(message.indexOf("Error")!=-1)
            {obj.showAlertMessage(message,"alert-danger");}
            else{ 
              obj.parent.loadListPatients();
              obj.showAlertMessage(message,"alert-success");
              setTimeout(function(){
                $("#"+obj.parent.modalRegisterName).modal('hide');
              },3000);
            }
      } ,
      (err) => {
          let message =  "Ocurrio un error, "+err;
          this.showAlertMessage(message,"alert-danger");
      });
  }
  createPatientUserDB():void{
    let obj = this;
    let entity:IUserModel = 
    { 
      tokenSession:obj.tokenUserSession,
      names       :obj.parent.userOwner.names,
      role        :obj.parent.userOwner.role,
      typeNif     :obj.parent.userOwner.typeNif,
      email       :((obj.parent.userOwner.email==null)?"":obj.parent.userOwner.email),
      numberNif   :obj.parent.userOwner.numberNif,
      sex         :obj.parent.userOwner.sex,
      address     :obj.parent.userOwner.address,
      movilNumber :obj.parent.userOwner.movilNumber,
      landLine    :obj.parent.userOwner.landLine,
      image       : "user-image-default.jpg"
    };
    //console.log("usuario: "+JSON.stringify(entity));
    this.userService.createUser(entity,0,1).then(
      (val) => {
        let message:string =  val;
        if(message.indexOf("Error")!=-1)
        {obj.showAlertMessage(message,"alert-danger");}
        else{ 
          obj.showAlertMessage(message,"alert-success");
          setTimeout(function(){
            $("#"+obj.parent.modalRegisterName).modal('hide');
          },4000);
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
  validateFields():boolean
  {  
     let obj = this;
     let value:boolean = true;
     let message:string = "Debe completar los campos: ";
     if(obj.parent.entityEditParent.kindPatient.idKind ==null){
        message  += "Tipo Paciente, ";
        value = false;
     } 
     if(obj.parent.entityEditParent.names == "")
     {
      message  += "Nombres, ";
      value = false;
     }
     
     if(obj.parent.userOwner.names!="" && !obj.parent.isEditMode){
        message  += "* Datos Propietario: ";
        if(obj.parent.userOwner.role.idRole == 0){
            message  += "Perfil, ";
            value = false;
        } 
        if(obj.parent.userOwner.typeNif.idDocument==0){
            message  += "Tipo Documento, ";
            value = false;
        } 
        if(obj.parent.userOwner.numberNif==null){
            message  += "Nro Documento, ";
            value = false;
        }
        if(obj.parent.userOwner.address     == "" && 
              obj.parent.userOwner.movilNumber == "" && 
              obj.parent.userOwner.landLine == ""){
              message += "Dirección, celular o teléfono fijo, ";
              value = false;
        }
     }
     //console.log(":: "+JSON.stringify(obj.parent.entityEditParent));
     if(!value){
          message = message.substr(0,message.length-2);
          this.showAlertMessage(message,"alert-danger");
      }
     return value;
  }



  showAlertMessage(message:string,typeAlert:string):void{
    let obj = this;
    obj.messageModal.showMessage = true;
    obj.messageModal.typeMessage = typeAlert;
    obj.messageModal.titleMessage = "Mensaje Sistema:";
    obj.messageModal.message     = message;
    setTimeout(function(){
        obj.messageModal.showMessage =false; 
    },3000);
  }

}
