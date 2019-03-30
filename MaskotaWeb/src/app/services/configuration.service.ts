import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Http, Headers, Response } from '@angular/http';
import { environment } from 'src/environments/environment';
import { IMenu, IResultResponse } from '../model/config.model';

@Injectable({
  providedIn: 'root'
})
export class ConfigurationService {
  
  private urlListMenu  = environment.urlApiRest+"/ws_menu.php"; 
  constructor(private http:HttpClient) { }
  
  getListMenu(role:number){
        return new Promise((resolve, reject) => {
              let header = this.getHeader();
              let parameters = new HttpParams();
              parameters = parameters.set('role', role.toString());
              this.http.post(this.urlListMenu,parameters,{headers:header}).subscribe(
                response => {
                    let obj:IResultResponse = response;
                    let roleList:IMenu[] = obj.data;
                    resolve(roleList); 
                  },
                  error => {
                    reject(error);
                  }
              )
        });
    }
    getHeader():HttpHeaders{
        let header = new HttpHeaders();
        header.set('Api-User-Agent', 'Example/1.0')
        //.append('Authorization','Basic REI0NDBENzdCM0RFREU3RTk3QkFCMzhDNjkwQ0NEN0U3OTFEMTVEQTo4OTFERkJFN0MyNUIwQjgyMTI0OTRBRUY4NUE2REEwQTk4ODZDMTMy')
        //.append('Content-Type','application/json')
        //.append("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept")
        .append('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        return header;
    }



}
