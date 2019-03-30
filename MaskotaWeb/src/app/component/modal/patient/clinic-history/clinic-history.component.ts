import { Component, OnInit, Input } from '@angular/core';
import { IPatientModel, IHistoryClinic } from 'src/app/model/patient.model';
import { IColumnTable } from 'src/app/model/config.model';
import * as $ from 'jquery';
declare var $:$;

@Component({
  selector: 'app-clinic-history',
  templateUrl: './clinic-history.component.html',
  styleUrls: ['./clinic-history.component.css']
})
export class ClinicHistoryComponent implements OnInit {

  titlePage:string; 
  filterDate:string;
  listReportColumns:IColumnTable[] = [];
  
  @Input('parentObj') parent:any;
  
  constructor() { }

  ngOnInit() {
      let obj = this;
      obj.titlePage = "Historia Clínica - Paciente: Federico";
      obj.listReportColumns = [
        {nameColumn:"Código"                  ,class:"ui-p-1  ui-column"},
        {nameColumn:"Fecha Cita"              ,class:"ui-p-2  ui-column"},
        {nameColumn:"Médico"                  ,class:"ui-p-3  ui-column"},
        {nameColumn:"Próxima Cita"            ,class:"ui-p-4  ui-column"},
        {nameColumn:"Monto Pago"              ,class:"ui-p-5  ui-column"},
        {nameColumn:""                        ,class:"ui-p-6  ui-column"}
        /*{nameColumn:"Pago"                    ,class:"ui-p-4  ui-column"},
        {nameColumn:"Signos Clínicos"         ,class:"ui-p-5  ui-column"},
        {nameColumn:"Estatura"                ,class:"ui-p-6  ui-column"},
        {nameColumn:"Peso"                    ,class:"ui-p-7  ui-column"},
        {nameColumn:"Temperatura"             ,class:"ui-p-8  ui-column"},
        {nameColumn:"Recomendaciones"         ,class:"ui-p-9  ui-column"},
        {nameColumn:"Diagnóstico"             ,class:"ui-p-10  ui-column"},
        {nameColumn:"Próxima Cita"            ,class:"ui-p-11  ui-column"},
        {nameColumn:"Tratamiento"             ,class:"ui-p-12  ui-column"},
        {nameColumn:"Vacuna"                  ,class:"ui-p-13  ui-column"},
        {nameColumn:"Quimioterapia"           ,class:"ui-p-14  ui-column"},
        {nameColumn:"Vacuna Completa"         ,class:"ui-p-15  ui-column"},
        {nameColumn:"Desparacitado"           ,class:"ui-p-16  ui-column"},
        {nameColumn:"Tiene Operaciones"       ,class:"ui-p-17  ui-column"},
        {nameColumn:"Pulgas"                  ,class:"ui-p-18  ui-column"},
        {nameColumn:"Garrapatas"              ,class:"ui-p-19  ui-column"},
        {nameColumn:"Hongos"                  ,class:"ui-p-20  ui-column"},
        {nameColumn:"Otitis"                  ,class:"ui-p-21  ui-column"},
        {nameColumn:"Baño Estandar"           ,class:"ui-p-22  ui-column"},
        {nameColumn:"Baño Medicado"           ,class:"ui-p-23  ui-column"},
        {nameColumn:"Corte"                   ,class:"ui-p-24 ui-column"},
        {nameColumn:"Hizo Efectiva Promo"     ,class:"ui-p-25  ui-column"},*/
      ];
  }

  showModalDetailHistory(historySelect:IHistoryClinic):void{
    let obj = this;
    obj.parent.historyClinicDetailSelect = historySelect;
    obj.parent.titleModal = "Historia Clínica: "+historySelect.idHistory+
                            " - Paciente: "+obj.parent.entityEditParent.names;
    obj.parent.loadDataDetail();
    $("#"+obj.parent.modalHistoryClinic).modal('hide'); 
    $("#"+obj.parent.modalMedicalAppointment).modal('show');
    $("#"+obj.parent.modalMedicalAppointment).on('shown.bs.modal', function(){
      
    });
  }
  
}
