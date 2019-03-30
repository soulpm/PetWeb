import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Http, Headers, Response, RequestOptions } from '@angular/http';
import { environment } from '../../environments/environment';
import { ConfigurationService } from './configuration.service';
import { ILogin, ILoginResponse, IUserAccount } from '../model/login.model';
import { IResultResponse } from '../model/config.model';

@Injectable({
  providedIn: 'root'
})
export class LoginService {
   
   private UrlLogin       = environment.urlApiRest+"/ws_login.php"; 
   private UrlLogout      = environment.urlApiRest+"/ws_user.php";
   private UrlAccountUser = environment.urlApiRest+"/ws_user.php";
   constructor(private http:HttpClient,private configService:ConfigurationService) {
      
   }

   loginAccount(loginModel:ILogin):any{
      let obj = this;
      return new Promise((resolve, reject) => {
        let loginResponse:ILoginResponse = {
            isLoginCorrect : false,
            message        : "Datos no válidos",
            stateLogin     : 0,
            userAccount    : {}
        };
        let header = obj.configService.getHeader();
        let parameters = new HttpParams();
        parameters = parameters.set('usr', loginModel.userId);
        parameters = parameters.set('pwd', loginModel.password);
        loginResponse.showMessage          = true;
        loginResponse.typeMessage          = "alert-danger";
        loginResponse.titleMessage         = "Error!";
                
        obj.http.post(obj.UrlLogin,parameters,{headers:header}).subscribe(
            data => {
              let result:IResultResponse = data; 
              if(result.responseCode == 200){
                sessionStorage.setItem("user_session",result.data);
                loginResponse.isLoginCorrect       = true;
                loginResponse.typeMessage          = "alert-success";
                loginResponse.titleMessage         = "";
                loginResponse.message              = "Bienvenid@ al Sistema";
                resolve(loginResponse);
              } 
              else{
                loginResponse.isLoginCorrect       = false;
                loginResponse.message              = result.data;
                reject(loginResponse);
              }
            },
            error => {
                loginResponse.message             = "error en llamada de servicio";  
                reject(loginResponse);
            }
        )
      });
   }

  logoutAccount():any{
   return new Promise((resolve, reject) => {
            let obj = this;
            let header = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type',"logout");
            parameters = parameters.set('user_session',sessionStorage.getItem("user_session"));
            this.http.post(this.UrlLogout,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let message = obj.data;
                      resolve(message); 
                },
                error => {
                  reject(error);
                }
            )
      }); 
  }
  getAccountUser():any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "account");
            let tokenSession:string =  sessionStorage.getItem("user_session");
            parameters = parameters.set('user_session',tokenSession);
            obj.http.post(this.UrlAccountUser,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let userAccount:IUserAccount = obj.data;
                  if(userAccount != null && userAccount != ""){
                      resolve(userAccount);
                  } 
                },    
                error => {
                  reject(error);
                }
            )
      });
   }

}
