import { Component, OnInit } from '@angular/core';
import { IMenu } from 'src/app/model/config.model';
import { IUserModel } from 'src/app/model/user.model';
import { IUserAccount } from 'src/app/model/login.model';
import { Router } from '@angular/router';
import { LoginService } from 'src/app/services/login.service';
import { ConfigurationService } from 'src/app/services/configuration.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  menuList:any;
  userAccount:IUserAccount = {};
  iniPath:string;
  titleSystem:string;

  constructor(
    private router:Router,
    private loginService:LoginService,
    private configService:ConfigurationService
  ) { }

  ngOnInit() {
    let obj = this;
    obj.getUserAccount();
    obj.iniPath = environment.initPage;  
    obj.titleSystem = "Sistema Atención Mascotas";
  }

  loadMenu(role):void{
    let obj = this;
    obj.configService.getListMenu(role).then(
      (val) => {
        obj.menuList = val;
      } ,
      (err) => {

      });
  }
  getUserAccount():void{
    let obj = this;
    obj.loginService.getAccountUser().then(
      (val) => {
        obj.userAccount = val;
        obj.loadMenu(obj.userAccount.role.idRole);
      } ,   
      (err) => {
        obj.userAccount = {};
        obj.router.navigate(['']);
      });
  }
  logout():void{
    let obj = this;
    
    this.loginService.logoutAccount().then(
      (val) => {
        obj.userAccount = {};
        sessionStorage.removeItem("user_session");
        obj.router.navigate(['login']);
      } ,
      (err) => {
        obj.userAccount = {};
        obj.router.navigate(['login']);
      });
  }

  changeMenu(menu:IMenu):void{
    let obj = this; 
    for(let k=0;k<obj.menuList.length;k++){
      obj.menuList[k].active = "";   
      if(obj.menuList[k].idMenu == menu.idMenu){
        obj.menuList[k].active = "active";
      }
    }
  }

}
