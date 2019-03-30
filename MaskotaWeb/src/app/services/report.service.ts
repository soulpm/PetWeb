import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpHeaders, HttpParams, HttpClient } from '@angular/common/http';
import { ConfigurationService } from './configuration.service';
import { IResultResponse } from '../model/config.model';
import { IReportAttention } from '../model/report.model';

@Injectable({
  providedIn: 'root'
})
export class ReportService {

  private UrlListBase           = environment.urlApiRest;
  private UrlReportAttentionXls = this.UrlListBase+"/excel/ws_report_attention_xls.php";
  private UrlReportAttention    = this.UrlListBase+"/ws_report.php";

  constructor(private http:HttpClient,private configService:ConfigurationService) { }

  getReportAttentionExcel(patient:string,dateIni:string,dateEnd:string):void{
      patient = (patient==null)?"":patient;
      window.location.href =  this.UrlReportAttentionXls+
                              "?token="+sessionStorage.getItem("user_session")+
                              "&patient="+patient+
                              "&date_ini="+dateIni+
                              "&date_end="+dateEnd;
  } 
 
  getReportAttention(patient:string,dateIni:string,dateEnd:string):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "report_attention");
            parameters = parameters.set('token',sessionStorage.getItem("user_session"));
            parameters = parameters.set('patient', patient);
            parameters = parameters.set('date_ini', dateIni);
            parameters = parameters.set('date_end',dateEnd);
            this.http.post(obj.UrlReportAttention,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let list:IReportAttention[] = obj.data;
                      resolve(list); 
                },
                error => {
                  reject(error);
                }
            )
      });
  }

}
