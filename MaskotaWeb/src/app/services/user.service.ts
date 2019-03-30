import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpHeaders, HttpParams, HttpClient } from '@angular/common/http';
import { ConfigurationService } from './configuration.service';
import { IUserModel } from '../model/user.model';
import { IResultResponse } from '../model/config.model';
import { IRoleModel } from '../model/login.model';
import { IUserPatientModel } from '../model/patient.model';

@Injectable({
  providedIn: 'root'
})      
export class UserService {

  private UrlBaseUser           = environment.urlApiRest+"/ws_user.php";
  
  constructor(private http:HttpClient,private configService:ConfigurationService) { }
  getListUsers(userEntity:IUserModel):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list");
            parameters = parameters.set('token_session', userEntity.tokenSession);
            parameters = parameters.set('id', userEntity.userId.toString());
            parameters = parameters.set('name', userEntity.names);
            parameters = parameters.set('role',userEntity.role.idRole.toString());
            parameters = parameters.set('type_doc', userEntity.typeNif.idDocument.toString());
            parameters = parameters.set('state',userEntity.estate.idState.toString());
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listUsers:IUserModel[] = obj.data;
                      resolve(listUsers); 
                },
                error => {
                  reject(error);
                }
            )
      });
  }
  getListMedics(userEntity:IUserModel):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_medics");
            parameters = parameters.set('token_session', userEntity.tokenSession);
            parameters = parameters.set('id', userEntity.userId.toString());
            parameters = parameters.set('name', userEntity.names);
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listUsers:IUserModel[] = obj.data;
                      resolve(listUsers); 
                },
                error => {
                  reject(error);
                }
            )
      });
  }
  getListClientPatientUser(userEntity:IUserPatientModel,patient:number):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_client");
            parameters = parameters.set('token_session', userEntity.tokenSession);
            parameters = parameters.set('name', userEntity.names);
            parameters = parameters.set('nif', userEntity.numberNif);
            parameters = parameters.set('patient', patient.toString());
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listUsers:IUserPatientModel[] = obj.data;
                      resolve(listUsers); 
                },
                error => {
                  reject(error);
                }
            )
      });
  }
  getListClientPatientOwner(tokenSession:string,patient:number):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_client_owner");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('patient', patient.toString());
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listUsers:IUserPatientModel[] = obj.data;
                      resolve(listUsers); 
                },
                error => {
                  reject(error);
                }
            )
      });
  }
  getListRole(tokenSession:string):any{
      return new Promise((resolve, reject) => {
            let obj    = this;
            let header = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_role");
            parameters = parameters.set('token_session',tokenSession);
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listRole:IRoleModel[] = obj.data;
                      resolve(listRole); 
                },
                error => {
                  reject(error);
                }
            )
      });
  }
  createUser(userEntity:IUserModel,image:number,w_owner:number):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            console.log("user: "+JSON.stringify(userEntity));
            parameters = parameters.set('type', "create");
            parameters = parameters.set('token_session', userEntity.tokenSession);
            parameters = parameters.set('role',userEntity.role.idRole.toString());
            parameters = parameters.set('names', userEntity.names);
            parameters = parameters.set('email', userEntity.email);
            parameters = parameters.set('type_doc', userEntity.typeNif.idDocument.toString());
            parameters = parameters.set('nif',userEntity.numberNif);
            parameters = parameters.set('sex',userEntity.sex.toString());
            parameters = parameters.set('address',userEntity.address);
            parameters = parameters.set('movilNumber',userEntity.movilNumber);
            parameters = parameters.set('landLine',userEntity.landLine);
            parameters = parameters.set('image',image.toString());
            parameters = parameters.set('w_owner',w_owner.toString());
            console.log("datos: "+JSON.stringify(userEntity)+" ; "+w_owner);
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
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
  editUser(userEntity:IUserModel,image:number):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "edit");
            parameters = parameters.set('token_session', userEntity.tokenSession);
            parameters = parameters.set('id_user',userEntity.userId.toString());
            parameters = parameters.set('role',userEntity.role.idRole.toString());
            parameters = parameters.set('names', userEntity.names);
            parameters = parameters.set('email', userEntity.email);
            parameters = parameters.set('type_doc', userEntity.typeNif.idDocument.toString());
            parameters = parameters.set('nif',userEntity.numberNif);
            parameters = parameters.set('sex',userEntity.sex.toString());
            parameters = parameters.set('address',userEntity.address);
            parameters = parameters.set('movilNumber',userEntity.movilNumber);
            parameters = parameters.set('landLine',userEntity.landLine);
            parameters = parameters.set('image',image.toString());
            parameters = parameters.set('estate',userEntity.estate.idState.toString());
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
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
  changeCredential(userId:number,password:string):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "change_credential");
            parameters = parameters.set('id_user',userId.toString());
            parameters = parameters.set('password',password);
            this.http.post(obj.UrlBaseUser,parameters,{headers:header}).subscribe(
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
  

}
