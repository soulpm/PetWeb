import { Component, OnInit, ViewChild } from '@angular/core';
import { IPatientModel, IKindPatientModel, IUserPatientModel, IClinicSigns, IHistoryClinic, ISexPatientModel } from 'src/app/model/patient.model';
import * as $ from 'jquery';
import { Router } from '@angular/router';
import { PatientService } from 'src/app/services/patient.service';
import { IUserModel } from 'src/app/model/user.model';
import { UserService } from 'src/app/services/user.service';
declare var $:$;

@Component({
  selector: 'app-patients',
  templateUrl: './patients.component.html',
  styleUrls: ['./patients.component.css']
})
export class PatientsComponent implements OnInit {

  titlePage:string  = "Pacientes";
  filterKind:IKindPatientModel = {idKind:0};
  filterName:string = "";
  @ViewChild('dtPatients') dataTable: any;
  listPatient:IPatientModel[] = [];
  listKind:IKindPatientModel [] = [];
  listKindRegister:IKindPatientModel[] = [];
  listPatientSex:ISexPatientModel[] = [];
  listHistoryClinic:IHistoryClinic[] = [];
  historyClinicDetailSelect:IHistoryClinic;
  parentComponent:PatientsComponent;
  titleModal:string;
  modalRegisterName:string          = "modalPatient";
  modalAssignOwnerName:string       = "modalAssignOwner";
  modalMedicalAppointment:string    = "modalMedicalAppointment";
  modalHistoryClinic:string         = "modalHistoryClinic";
  entityEditParent:IPatientModel    = {kindPatient:{},sex:{}};
  userOwner:IUserModel= {typeNif:{},estate:{},role:{}}; 
  isEditMode:boolean;
  tokenUserSession:string;
  patientOwner:number;
  inputDateBorn:any;
  yearPatient:number;
  monthPatient:number;
  listMedics:IUserModel[]            = [];         
  inputMedic:IUserModel;
  listSignClinic:IClinicSigns[]      = [];
  listDiagnostic:IClinicSigns []     = [];
  listVaccine:IClinicSigns[]        = [];
  listTreatments:IClinicSigns []     = [];
  signClinic:string                  = "";
  listClientUsers:IUserModel[]       = [];
  listUserOwnerInit:IUserModel[]     = [];
  
  //--- MEDICAL APPOINTMENT
  dateMedical:any;
  nextDateMedical:any;
  inputQuimio:string;
  inputTreatment:string;
  inputVaccine:string;
  inputPayment:number;
  inputRecomend:string;
  inputTemperature:number;
  selectKnownItem:number;
  checkVaccineCompleted:boolean;
  checkDesparacitado:boolean;
  checkWithOperacion:boolean;
  checkPulgas:boolean; 
  checkGarrapata:boolean; 
  checkHongo:boolean; 
  checkOtitis:boolean; 
  checkBanioStandar:boolean; 
  checkBanioMedicado:boolean; 
  checkPromoGratis:boolean; 
  checkCorte:boolean;
  inputSize:number;
  inputWeight:number;
  inputDiagnostic:IClinicSigns;
  inputNextDate:any;
  checkFormaDirecta:boolean;
  checkRecomendado:boolean;
  checkPublicidadRadial:boolean;
  checkPublicidadVolante:boolean;
  checkPublicidadInternet:boolean;
  checkIsClient:boolean;  
  
  constructor(
      private router:Router,
      private patientService:PatientService,
      private userService:UserService
    ) { }

  ngOnInit() {
    let obj = this;
    obj.tokenUserSession = sessionStorage.getItem("user_session");
    obj.configListItems();
    obj.loadListPatients();
    /*obj.listPatient = [
      {idPatient:1,kindPatient:{idKind:1,name:"Perro"},name:"Federico",breed:"Mestizo",sex:"Macho",color:"Negro",age:1}
    ];*/
  }
  configListItems():void{
      let obj = this;
      obj.parentComponent = this;
      obj.listKind = [
        { idKind:0,name:"Todos"},
        { idKind:1,name:"Perro"},
        { idKind:2,name:"Gato"}
      ];
      obj.filterKind = obj.listKind[0];
      obj.listKindRegister = [
        { idKind:1,name:"Perro"},
        { idKind:2,name:"Gato"}
      ];
      obj.listPatientSex = [
        {idSex:1,name:"Macho"},
        {idSex:2,name:"Hembra"}
      ];
  }
  loadListPatients():void{
     let obj = this;
     let tokenSession:string = sessionStorage.getItem("user_session");
     let filter:IPatientModel = {
        idPatient:0,
        names:obj.filterName,
        kindPatient:obj.filterKind
     }
     obj.dataTable.reset();
     obj.patientService.getListPatients(filter,tokenSession).then(
      (val) => {
         obj.listPatient = val;
      }, 
      (err) => {
         console.log("error en llamada servicio lista pacientes: "+JSON.stringify(err));
      });
  }
  getListClientPatientUser(filterName:string,filterNif:string,patient:number):void{
      let obj = this;
      let entity:IUserPatientModel = 
      { tokenSession:obj.tokenUserSession,names:filterName,numberNif:filterNif};
      this.userService.getListClientPatientUser(entity,patient).then(
      (val) => {
        obj.listClientUsers = val;
      } , 
      (err) => {
        
      });
  }
  getListClientPatientOwner(patient:number):void{
      let obj = this;
      let entity:IUserPatientModel = 
      this.userService.getListClientPatientOwner(obj.tokenUserSession,patient).then(
      (val) => {
        obj.listClientUsers = val;
        obj.listUserOwnerInit = val;
      } , 
      (err) => {
        
      });
  }
  getListAttention(idPatient:number):void{
    let obj = this;
      this.patientService.getListAttention(0,idPatient,obj.tokenUserSession).then(
      (val) => {
        obj.listHistoryClinic = val;
      } , 
      (err) => {
        
      });
  }
  showModalNew():void{
    let obj = this; 
    obj.titleModal = "Ingreso Paciente";
    obj.isEditMode = false;
    obj.inputDateBorn = null;
    obj.entityEditParent = {
      names:"",year:0,month:0,color:"",breed:"",
      dateBorn:null,idPatient:-1, kindPatient:{idKind:1},
      sex:{idSex:1}
    };
    obj.userOwner =  {
      role    :{idRole:4},
      typeNif :{idDocument:3},
      estate  :{idState:1},
      sex     :"M",
      names:"",movilNumber:"",address:"",landLine:"",email:"",image:""};
    obj.historyClinicDetailSelect = null;
    $("#"+obj.modalRegisterName).modal('show');
  }
  showModalEdit(patient:IPatientModel):void{
      let obj           = this; 
      obj.titleModal    = "Edición Paciente";
      obj.isEditMode    = true;
      let dt:any = {};
      if(patient.dateBorn!=null){
        let dtSplit = patient.dateBorn.split("-");
        dt  = {year:parseInt(dtSplit[0]),month:parseInt(dtSplit[1]),day:parseInt(dtSplit[2])};
      }
      obj.inputDateBorn = dt;
      obj.entityEditParent = patient;
      obj.historyClinicDetailSelect = null;
      $("#"+obj.modalRegisterName).modal('show');

  }
  showHistoryClinic(patient:IPatientModel):void{
    let obj = this;
    obj.titleModal = "Detalle Paciente - "+patient.names;
    obj.isEditMode = false;
    obj.entityEditParent = patient;
    obj.loadDataDetailInitial();
    obj.getListAttention(patient.idPatient);
    obj.historyClinicDetailSelect = null;
    $("#"+obj.modalHistoryClinic).modal('show'); 
  }
  showModalAssignOwner(patient:IPatientModel):void{
      let obj = this; 
      obj.titleModal = "Propietario Mascota";
      obj.isEditMode = false;
      obj.entityEditParent = patient;
      obj.patientOwner = patient.idPatient;
      //obj.getListClientPatientUser("","",obj.patientOwner);
      obj.historyClinicDetailSelect = null;
      obj.getListClientPatientOwner(patient.idPatient);
      $("#"+obj.modalAssignOwnerName).modal('show');
  }
  showMedicalAppointment(patient):void{
      let obj = this; 
      obj.titleModal = "Registro de Atención";
      obj.isEditMode = false;
      obj.loadDataDetailInitial();
      obj.entityEditParent = patient;
      obj.loadClinicSigns(false);
      obj.loadDiagnostic();
      obj.loadVaccines(); 
      obj.loadMedics();
      obj.historyClinicDetailSelect = null;
      $("#"+obj.modalMedicalAppointment).modal('show');
  }
  loadClinicSigns(isLoad:boolean):void{
     let obj = this;
     let tokenSession:string = sessionStorage.getItem("user_session");
     obj.patientService.getSignClinic(0,"",tokenSession).then(
      (val) => {
         if(val!=null){
          obj.listSignClinic = val;
            if(isLoad){
              if(obj.historyClinicDetailSelect.idClinicSign!="")
              {
                let sClinic =  obj.historyClinicDetailSelect.idClinicSign.split(";");
                for(let k=0;k<val.length;k++){
                  obj.listSignClinic[k].isChecked = false;
                  for(let j=0;j<sClinic.length;j++){
                    if(obj.listSignClinic[k].name== sClinic[j]){
                       obj.listSignClinic[k].isChecked = true;
                       break;
                    }
                  }
                }
              }

              obj.checkPulgas           =  (obj.historyClinicDetailSelect.itvePulgas=="NO"?false:true);
              obj.checkGarrapata        =  (obj.historyClinicDetailSelect.itveGarrapata=="NO"?false:true);
              obj.checkHongo            =  (obj.historyClinicDetailSelect.itveHongos=="NO"?false:true);
              obj.checkOtitis           =  (obj.historyClinicDetailSelect.itveOtitis=="NO"?false:true);
              obj.checkBanioStandar     =  (obj.historyClinicDetailSelect.itveBanioStandar=="NO"?false:true);
              obj.checkBanioMedicado    =  (obj.historyClinicDetailSelect.itveBanioMedicado=="NO"?false:true);
              obj.checkCorte            =  (obj.historyClinicDetailSelect.itveCorte=="NO"?false:true);
              obj.checkPromoGratis      =  (obj.historyClinicDetailSelect.itvePromoGratis=="NO"?false:true);
            }
         }
      }, 
      (err) => {
         console.log("error en llamada servicio lista signos clinicos: "+JSON.stringify(err));
      });
    /*
     obj.signClinic = {idClinicSign:1,name:"Anorexia"};
    obj.listSignClinic = [
      {idClinicSign:1,name:"Anorexia"},
      {idClinicSign:2,name:"Diarrea"},
      {idClinicSign:3,name:"Diarrea/Sangre"},
      {idClinicSign:4,name:"Tenesmo"},
      {idClinicSign:5,name:"Vomitos"},
      {idClinicSign:6,name:"Vomitos c/Sangre"},
      {idClinicSign:7,name:"Relurgitación"},
      {idClinicSign:8,name:"Secreción Nasal"},
      {idClinicSign:9,name:"Tos"}
    ];*/
  }
  loadDiagnostic():void{
     let obj = this;
     let tokenSession:string = sessionStorage.getItem("user_session");
     obj.patientService.getDiagnostics(0,"",tokenSession).then(
      (val) => {
         if(val!=null){
          obj.listDiagnostic = val;
         }
      }, 
      (err) => {
         console.log("error en llamada servicio lista diagnosticos: "+JSON.stringify(err));
      });
  }
  loadVaccines():void{
     let obj = this;
     let tokenSession:string = sessionStorage.getItem("user_session");
     obj.patientService.getVaccines(0,"",tokenSession).then(
      (val) => {
         if(val!=null){
          obj.listVaccine = val;
         }
      }, 
      (err) => {
         console.log("error en llamada servicio lista diagnosticos: "+JSON.stringify(err));
      });
  }
  loadTreatments(diagnostic:number):void{
    let obj = this;
    obj.listTreatments = [];
    let tokenSession:string = sessionStorage.getItem("user_session");
    obj.patientService.getTreatments(0,"",diagnostic,tokenSession).then(
     (val) => {
        if(val!=null){
         obj.listTreatments = val;
        }
     }, 
     (err) => {
        console.log("error en llamada servicio lista tratamientos: "+JSON.stringify(err));
     });
 }
  

  loadMedics():void{
    let obj = this;
    let tokenSession:string = sessionStorage.getItem("user_session");
    let filterEntity:IUserModel = {
      tokenSession:obj.tokenUserSession,
      userId:0,
      names:""
    }
    obj.userService.getListMedics(filterEntity).then(
     (val) => {
        if(val!=null){
         obj.listMedics = val;
         obj.inputMedic = val[0];
        }
     }, 
     (err) => {
        console.log("error en llamada servicio lista pacientes: "+JSON.stringify(err));
     });
  }

  loadDataDetail():void{
    let obj = this;
            obj.loadDataDetailInitial();
            obj.signClinic            = "";
            obj.inputMedic            = obj.historyClinicDetailSelect.medic;
            let date1                 = obj.historyClinicDetailSelect.dateRegister.split("-");
            obj.dateMedical           = {year  : parseInt(date1[0]),
                                         month : parseInt(date1[1]),
                                         day   : parseInt(date1[2]) };
            obj.inputSize             = obj.historyClinicDetailSelect.stature;
            obj.inputWeight           = obj.historyClinicDetailSelect.weight;
            obj.inputDiagnostic       = {name:obj.historyClinicDetailSelect.diagnostic};
            obj.inputQuimio           =  obj.historyClinicDetailSelect.chemotherapy;
            if(obj.historyClinicDetailSelect.nextDate!=""){
            let date2                 =  obj.historyClinicDetailSelect.nextDate.split("-");
            obj.nextDateMedical       =  {year  : parseInt(date2[0]),
                                          month : parseInt(date2[1]),
                                          day   : parseInt(date2[2]) };
            }
            obj.inputRecomend         =  obj.historyClinicDetailSelect.recommend; 
            obj.inputTreatment        =  obj.historyClinicDetailSelect.treatment;
            obj.inputVaccine          =  obj.historyClinicDetailSelect.vaccine;
            obj.inputPayment          =  obj.historyClinicDetailSelect.payment;
            obj.inputTemperature      =  obj.historyClinicDetailSelect.temperature;
            obj.checkVaccineCompleted =  (obj.historyClinicDetailSelect.vaccineCompleted=="NO"?false:true);
            obj.checkDesparacitado    =  (obj.historyClinicDetailSelect.desparacitado=="NO"?false:true);
            obj.checkWithOperacion    =  (obj.historyClinicDetailSelect.withOperation=="NO"?false:true);
            obj.checkFormaDirecta       = false;
            obj.checkRecomendado        = false;
            obj.checkPublicidadRadial   = false;
            obj.checkPublicidadVolante  = false;
            obj.checkPublicidadInternet = false;
            obj.checkIsClient           = false;
            switch(obj.historyClinicDetailSelect.socialKnown)
            { 
              case "1": obj.checkFormaDirecta       = true; break;
              case "2": obj.checkRecomendado        = true; break;
              case "3": obj.checkPublicidadRadial   = true; break;
              case "4": obj.checkPublicidadVolante  = true; break;
              case "5": obj.checkPublicidadInternet = true; break;
              case "6": obj.checkIsClient           = true; break;
            }
            obj.loadClinicSigns(true);
  }

  loadDataDetailInitial():void{
    let obj = this;
    let hc:IHistoryClinic;
            //clinicSign            : toString(obj.parent.signClinic),
            //medic                 : obj.parent.inputMedic,
            let currentDate:Date = new Date();
            obj.signClinic      = "";
            obj.selectKnownItem = 0;
            obj.nextDateMedical = {};
            obj.dateMedical = 
            {year:currentDate.getFullYear(),month:currentDate.getMonth()+1,day:currentDate.getDate() };
            $("#chk_DI").prop( "checked", false );
            $("#chk_RE").prop( "checked", false );
            $("#chk_PR").prop( "checked", false );
            $("#chk_PV").prop( "checked", false );
            $("#chk_PI").prop( "checked", false );
            $("#chk_YC").prop( "checked", false );
            obj.inputSize             = 0;
            obj.inputWeight           = 0;
            //obj.inputDiagnostic.name   = hc.diagnostic;
            obj.inputQuimio           =  "";
            //nextDate              : dateNext,
            obj.inputRecomend         =  ""; 
            obj.inputTreatment        =  "";
            obj.inputVaccine          =  "";
            obj.inputPayment          =  0;
            obj.inputTemperature      =  0;
            obj.checkVaccineCompleted =  false;
            obj.checkDesparacitado    =  false;
            obj.checkWithOperacion    =  false;
            obj.selectKnownItem       =  0;
            obj.checkPulgas           =  false;
            obj.checkGarrapata        =  false; 
            obj.checkHongo            =  false;
            obj.checkOtitis           =  false;
            obj.checkBanioStandar     =  false;
            obj.checkBanioMedicado    =  false;
            obj.checkCorte            =  false;
            obj.checkPromoGratis      =  false;
  }



}
