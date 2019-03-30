import { Component, OnInit, Input } from '@angular/core';
import { IMessageModel } from 'src/app/model/config.model';
import { environment } from 'src/environments/environment';
import { IClinicSigns, IHistoryClinic, IPatientModel } from 'src/app/model/patient.model';
import { toString,toInteger } from '../../../../utilities/utility';
import * as $ from 'jquery';
import { PatientService } from 'src/app/services/patient.service';
declare var $:$;

@Component({
  selector: 'app-modal-medical-appointment',
  templateUrl: './modal-medical-appointment.component.html',
  styleUrls: ['./modal-medical-appointment.component.css']
})
export class NewMedicalAppointmentComponent implements OnInit {
  root_path_image:string                  = environment.pathImage;
  messageModal:IMessageModel              = {};
  tokenUserSession:string                 = "";
  
  @Input('parentObj') parent:any;
  
  listSignClinic:IClinicSigns[] = [];
  constructor(private patientService:PatientService) { }

  ngOnInit() {
    let obj = this;
    obj.tokenUserSession = sessionStorage.getItem("user_session");       
    obj.loadSelects();
    let currentDate:Date = new Date();
    obj.parent.selectKnownItem = 0;
    obj.parent.dateMedical = {year:currentDate.getFullYear(),month:currentDate.getMonth()+1,day:currentDate.getDate() };
    
  }

  loadSelects():void{
    let obj = this;
    /*obj.signClinic = {idClinicSign:1,value:"Anorexia"};
    obj.listSignClinic = [
      {idClinicSign:1,value:"Anorexia"},
      {idClinicSign:2,value:"Diarrea"},
      {idClinicSign:3,value:"Diarrea/Sangre"},
      {idClinicSign:4,value:"Tenesmo"},
      {idClinicSign:5,value:"Vomitos"},
      {idClinicSign:6,value:"Vomitos c/Sangre"},
      {idClinicSign:7,value:"Relurgitación"},
      {idClinicSign:8,value:"Secreción Nasal"},
      {idClinicSign:9,value:"Tos"}
    ];*/
  }
  selectSignClinics(item:IClinicSigns):void{
    let obj = this;
    if(obj.parent.signClinic.indexOf(item.name)!=-1)
    { 
      obj.parent.signClinic = obj.parent.signClinic.replace(item.name+";","");
    }
    else{
      obj.parent.signClinic += item.name+";";
    }
    //console.log(": "+JSON.stringify(obj.parent.signClinic));
  }
  selectKnown(item:number):void{
      $("#chk_DI").prop( "checked", false );
      $("#chk_RE").prop( "checked", false );
      $("#chk_PR").prop( "checked", false );
      $("#chk_PV").prop( "checked", false );
      $("#chk_PI").prop( "checked", false );
      $("#chk_YC").prop( "checked", false );
      this.parent.selectKnownItem = item;
      switch(item){
        case 1: $("#chk_DI").prop( "checked", true ); break;
        case 2: $("#chk_RE").prop( "checked", true ); break;
        case 3: $("#chk_PR").prop( "checked", true ); break;
        case 4: $("#chk_PV").prop( "checked", true ); break;
        case 5: $("#chk_PI").prop( "checked", true ); break;
        case 6: $("#chk_YC").prop( "checked", true ); break;
      }
  }
  save():void{
      let obj = this;
      if(obj.validateFields()){
          let dateReg:string    = "";
          //console.log("obj.dateMedical: "+JSON.stringify(obj.dateMedical));
          if(obj.parent.dateMedical!=null){
            dateReg = ""+obj.parent.dateMedical.year+"-"+((obj.parent.dateMedical.month<10)?"0"+obj.parent.dateMedical.month:obj.parent.dateMedical.month)+"-"+((obj.parent.dateMedical.day<10)?"0"+obj.parent.dateMedical.day:obj.parent.dateMedical.day)};
          let dateNext:string   = "";
          if(obj.parent.nextDateMedical!=null){
            dateNext = ""+obj.parent.nextDateMedical.year+"-"+((obj.parent.nextDateMedical.month<10)?"0"+obj.parent.nextDateMedical.month:obj.parent.nextDateMedical.month)+"-"+((obj.parent.nextDateMedical.day<10)?"0"+obj.parent.nextDateMedical.day:obj.parent.nextDateMedical.day)};
          let entity:IHistoryClinic = {
            patient               : obj.parent.entityEditParent,
            clinicSign            : toString(obj.parent.signClinic),
            medic                 : obj.parent.inputMedic,
            dateRegister          : dateReg,
            stature               : ((obj.parent.inputSize==null)?0:obj.parent.inputSize),
            weight                : ((obj.parent.inputWeight==null)?0:obj.parent.inputWeight),
            diagnostic            : toString((obj.parent.inputDiagnostic==null)?"":obj.parent.inputDiagnostic.name),
            chemotherapy          : toString(obj.parent.inputQuimio),
            nextDate              : dateNext,
            recommend             : toString(obj.parent.inputRecomend), 
            treatment             : toString(obj.parent.inputTreatment),
            vaccine               : toString(obj.parent.inputVaccine),
            payment               : obj.parent.inputPayment,
            temperature           : ((obj.parent.inputTemperature==null)?0:obj.parent.inputTemperature),
            vaccineCompleted      : ((obj.parent.checkVaccineCompleted)?1:0),
            desparacitado         : ((obj.parent.checkDesparacitado)?1:0),
            withOperation         : ((obj.parent.checkWithOperacion)?1:0),
            socialKnown           : obj.parent.selectKnownItem,
            itvePulgas            : ((obj.parent.checkPulgas)?1:0),
            itveGarrapata         : ((obj.parent.checkGarrapata)?1:0), 
            itveHongos            : ((obj.parent.checkHongo)?1:0),
            itveOtitis            : ((obj.parent.checkOtitis)?1:0),
            itveBanioStandar      : ((obj.parent.checkBanioStandar)?1:0),
            itveBanioMedicado     : ((obj.parent.checkBanioMedicado)?1:0),
            itveCorte             : ((obj.parent.checkCorte)?1:0),
            itvePromoGratis       : ((obj.parent.checkPromoGratis)?1:0)
          }; 
          //console.log("DATO: "+JSON.stringify(entity));
          let tokenSession = sessionStorage.getItem("user_session");
          obj.patientService.createAttention(entity,tokenSession).then(
            (val) => {
                  let message:any =  val;
                  if(message.indexOf("Error")!=-1)
                  {obj.showAlertMessage(message,"alert-danger");}
                  else{ 
                    obj.parent.loadListPatients();
                    obj.showAlertMessage(message,"alert-success");
                    setTimeout(function(){
                      $("#"+obj.parent.modalMedicalAppointment).modal('hide');
                    },2000);
                  }
            } ,
            (err) => {
                let message =  "Ocurrio un error, "+err;
                this.showAlertMessage(message,"alert-danger");
            });
        }
  }
  returnHistoryClinic():void{
      let obj = this;
      obj.parent.historyClinicDetailSelect = null;
      obj.parent.showHistoryClinic(obj.parent.entityEditParent);
      $("#"+obj.parent.modalMedicalAppointment).modal('hide');
  }

  

  loadTreatment():void{
     let obj = this;
     obj.parent.loadTreatments(obj.parent.inputDiagnostic.idClinicSign);
  }
  validateFields():boolean{
    let obj = this;
    let result:boolean = true;
    let message:string = "Debe completar los campos: ";
     if(obj.parent.inputPayment == null)
     {
      message  += "Monto, ";
      result = false;
     }
     if(!result){
        message = message.substr(0,message.length-2);
        this.showAlertMessage(message,"alert-danger");
    }
    return result;
  }
  showAlertMessage(message:string,typeAlert:string):void{
    let obj = this;
    obj.messageModal.showMessage = true;
    obj.messageModal.typeMessage = typeAlert;
    obj.messageModal.titleMessage = "Mensaje Sistema:";
    obj.messageModal.message     = message;
    setTimeout(function(){
        obj.messageModal.showMessage =false; 
    },3500);
  }

}
