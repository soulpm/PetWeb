import { Component, OnInit, Input } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import * as $ from 'jquery';
declare var $:$;

@Component({
  selector: 'app-modal-credential-user',
  templateUrl: './modal-credential-user.component.html',
  styleUrls: ['./modal-credential-user.component.css']
})
export class ModalCredentialUserComponent implements OnInit {
  messageModal:string                     = "";
  typeAlertModal:string                   = "alert-danger";
  modalRegisterName:string                = "modalUserCredential";
  showMessageModal:boolean                = false;
  titleMessageModal:string                = "";
  @Input('parentObj') parent:any;

  constructor(private userService:UserService) { }

  ngOnInit() {
   
  }

  saveCredentials():void{
      let obj = this;
      if(obj.validateFields()){
      obj.userService.changeCredential(obj.parent.userEditParent.userId,this.parent.inputPassword).then(
          (val) => {
                let message:string =  val;
                if(message.indexOf("Error")!=-1)
                {obj.showAlertMessage(message,"alert-danger");}
                else{ 
                  obj.showAlertMessage(message,"alert-success");
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
  }

  validateFields():boolean{
     let obj = this;
     let valueReturn:boolean = false;
        if(obj.parent.inputConfirmPassword != "" ){
          if(obj.parent.inputPassword != "" ){
              if(obj.parent.inputPassword != "" &&  obj.parent.inputConfirmPassword !=""){          
                if(obj.parent.inputPassword == obj.parent.inputConfirmPassword){
                    valueReturn = true;
                }
                else{obj.showAlertMessage("Las contraseñas no coinciden, verifique","alert-danger");}
              }
              else{obj.showAlertMessage("Ingrese la contraseña a modificar","alert-danger");}
          }
          else{ obj.showAlertMessage("Debe ingresar una contraseña para modificar","alert-danger");}
        }
      else{obj.showAlertMessage("El campo confirmación de contraseña no puede estar vacío","alert-danger");}  
     return valueReturn;   
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
