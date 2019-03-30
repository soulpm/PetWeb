import { Component, OnInit } from '@angular/core';
import { ReportService } from 'src/app/services/report.service';
import { IReportAttention } from 'src/app/model/report.model';

@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.css']
})
export class ReportsComponent implements OnInit {

  titlePage:string                        = "Reportes";
  filterDateIni:any;
  filterDateEnd:any;
  filterPatient:string                    = "";
  fi:string;
  ff:string;
  showMessageModal:boolean                = false;
  titleMessageModal:string                = "";
  messageModal:string                     = "";
  typeAlertModal:string                   = "alert-danger";
  
  listReport:IReportAttention[] = [];

  constructor(private reportService:ReportService) { }

  ngOnInit() {
  }

  exportReport():void{
     let obj  = this;
     if(obj.listReport.length>0){
      obj.prepareDates();     
      obj.reportService.getReportAttentionExcel(obj.filterPatient,obj.fi,obj.ff);
     }
     else{
      let message =  "No hay registros para exportar, intente nuevamente";
      this.showAlertMessage(message,"alert-warning");
     }
  }
  showReport():void{
     let obj = this;
     if(obj.validateFields()){
        obj.prepareDates(); 
        obj.listReport = [];
        obj.reportService.getReportAttention(obj.filterPatient,obj.fi,obj.ff).then(
          (val) => {
            if(val!=null){
              obj.listReport = val;
            }
            else{
              let message =  "No hay registros para exportar, intente nuevamente";
              this.showAlertMessage(message,"alert-warning");
            }
        } , 
        (err) => {
            console.log("error en llamada servicio: "+JSON.stringify(err));
            let message =  "Error en llamada del servicio,\n"+JSON.stringify(err);
            obj.showAlertMessage(message,"alert-warning");
          });
      }
  }

  prepareDates():void{
    let obj = this;
    obj.fi = ""+obj.filterDateIni.year+"-"+((obj.filterDateIni.month<10)?"0"+obj.filterDateIni.month:obj.filterDateIni.month)+"-"+((obj.filterDateIni.day<10)?"0"+obj.filterDateIni.day:obj.filterDateIni.day);
    obj.ff = ""+obj.filterDateEnd.year+"-"+((obj.filterDateEnd.month<10)?"0"+obj.filterDateEnd.month:obj.filterDateEnd.month)+"-"+((obj.filterDateEnd.day<10)?"0"+obj.filterDateEnd.day:obj.filterDateEnd.day);
    
  }

  validateFields():boolean{
    let obj = this;
     let value:boolean = true;
     let message:string = "Debe completar los campos: ";
     if(obj.filterDateIni ==null){
        message  += "Fecha Inicio, ";
        value = false;
     } 
     if(obj.filterDateEnd == "")
     {
      message  += "Fecha Fin, ";
      value = false;
     }
     if(!value){
          message = message.substr(0,message.length-2);
          this.showAlertMessage(message,"alert-warning");
      }
     return value;
  }

  showAlertMessage(message:string,typeAlert:string):void{
    this.showMessageModal = true;
    this.typeAlertModal = typeAlert;
    this.messageModal  = message;
    let obj = this;
    setTimeout(function(){
        obj.showMessageModal=false; 
    },3500);
  }
}
