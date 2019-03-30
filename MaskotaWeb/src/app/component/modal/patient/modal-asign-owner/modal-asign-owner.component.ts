import { Component, OnInit, Input } from '@angular/core';
import { IMessageModel } from 'src/app/model/config.model';
import { environment } from 'src/environments/environment';
import { IUserModel } from 'src/app/model/user.model';
import { IUserPatientModel } from 'src/app/model/patient.model';
import { toString,toInteger } from '../../../../utilities/utility';
import { PatientService } from 'src/app/services/patient.service';
import * as $ from 'jquery';
declare var $:$;

@Component({
  selector: 'app-modal-asign-owner',
  templateUrl: './modal-asign-owner.component.html',
  styleUrls: ['./modal-asign-owner.component.css']
})
export class ModalAsignOwnerComponent implements OnInit {

  root_path_image:string                  = environment.pathImage;
  listUserOwnerInit:IUserModel[]          = [];
  messageModal:IMessageModel              = {};
  tokenUserSession:string                 = "";
  filterName:string;
  filterNif:string;
  @Input('parentObj') parent:any;
  
  constructor(private patientService:PatientService) { }

  ngOnInit() {
    let obj = this;
    obj.tokenUserSession = sessionStorage.getItem("user_session");       
  }
  findClients():void{
    let obj = this;
    obj.parent.getListClientPatientUser(toString(obj.filterName),toString(obj.filterNif),obj.parent.patientOwner);
  }
  assignOwner(userSelect:IUserPatientModel):void{
      let obj = this;
      let isOwner = -1; 
      (userSelect.isOwner==0)?isOwner = 1:isOwner = 0;
      for(let j=0;j<obj.parent.listClientUsers.length;j++){
        if(obj.parent.listClientUsers[j].userId == userSelect.userId){
            obj.parent.listClientUsers[j].isOwner = isOwner;
        }
      }
  }
  save():void{
    let obj = this;
    let patient:number =  obj.parent.patientOwner;
    let users:string = "";
    let userDrop:string  = "";
    
    if(obj.parent.listClientUsers!= null){
        for(let j=0;j<obj.parent.listClientUsers.length;j++){
            if(obj.parent.listClientUsers[j].isOwner == 1)
            {
              users += obj.parent.listClientUsers[j].userId+"||";
            }
        }
        if(obj.parent.listUserOwnerInit!=null){
          for(let j=0;j<obj.parent.listUserOwnerInit.length;j++){
              if(obj.parent.listUserOwnerInit[j].isOwner == 0)
              {
                userDrop += obj.parent.listUserOwnerInit[j].userId+"||";
              }
          }
        }
        if(users!=""){
          obj.patientService.createPatientOwners(users,userDrop,patient,obj.tokenUserSession).then(
            (val) => {
              let message:any =  val;
                if(message.indexOf("Error")!=-1)
                {obj.showAlertMessage(message,"alert-danger");}
                else{ 
                  obj.parent.loadListPatients();
                  obj.showAlertMessage(message,"alert-success");
                  setTimeout(function(){
                    $("#"+obj.parent.modalAssignOwnerName).modal('hide');
                  },2000);
                }
          } ,
          (err) => {
              let message =  "Ocurrio un error, "+err;
              this.showAlertMessage(message,"alert-danger");
          }
          );
        }
        else{
          obj.showAlertMessage("El paciente debe tener al menos un propietario asignado.","alert-warning");
        }
      }
      else{
        obj.showAlertMessage("Debe seleccionar un usuario para asignar como propietario","alert-warning");
      }
  }

  showAlertMessage(message:string,typeAlert:string):void{
    let obj = this;
    obj.messageModal.showMessage = true;
    obj.messageModal.titleMessage = "Mensaje Sistema:";
    obj.messageModal.typeMessage = typeAlert;
    obj.messageModal.message     = message;
    setTimeout(function(){
        obj.messageModal.showMessage =false; 
    },3500);
  }
  
}
