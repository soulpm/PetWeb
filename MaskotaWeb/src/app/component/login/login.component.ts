import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';
import { ILoginResponse, ILogin } from 'src/app/model/login.model';
import { LoginService } from 'src/app/services/login.service';
import * as sha1 from '../../../../node_modules/js-sha1/src/sha1.js';
@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  inputPassword:string            = "";
  inputUser:string                = "";
  pathImage:string;
  loginResponse:ILoginResponse    = {};
  loginInfo:ILogin                = {};
  isLoading:boolean;

  constructor(private loginService:LoginService,private router:Router) { }

  ngOnInit() {
    let obj = this; 
    obj.pathImage = environment.pathImage;
  }
  
  loginAction():void{ 
    let obj = this;
    if(!obj.isLoading){
      obj.validateData();
      if(obj.loginResponse.isLoginCorrect){
          obj.loginResponse = {};
          obj.loginInfo.userId    = sha1(obj.inputUser);
          obj.loginInfo.password  = sha1(obj.inputPassword);
          obj.isLoading = true;
          obj.loginService.loginAccount(obj.loginInfo).then(
            (val) => {
              obj.isLoading = false;
              obj.loginResponse = val;
              setTimeout(function(){obj.router.navigate([environment.initPage]);},1000);
            },
            (err) => {
              obj.isLoading =false;
              obj.loginResponse = err
            });
      }
    } 
  }
  validateData():void{
     let obj = this;
     let messageTemp:string                   = "";
     obj.loginResponse.isLoginCorrect        = false;
     if(obj.inputUser==""){
       messageTemp = " Usuario"; 
     }
     if(obj.inputPassword==""){
      if(obj.inputPassword!=""){messageTemp += " Contraseña";}
      else{messageTemp += " y Contraseña";}
     }
     if(messageTemp!=""){
        messageTemp = "Ingrese los campos: "+messageTemp;
        obj.loginResponse.typeMessage  = "alert-danger";
        obj.loginResponse.titleMessage = "Error!"; 
        obj.loginResponse.message      = messageTemp;
        obj.loginResponse.showMessage  = true;
     }
     else{ obj.loginResponse.isLoginCorrect        = true;}
  }

}
