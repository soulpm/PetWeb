import { Injectable }                                               from '@angular/core';
import { Router, CanActivateChild}                                                    from '@angular/router';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable }                                               from 'rxjs';
import { LoginService } from './services/login.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate,CanActivateChild {
  constructor(  private loginService:LoginService,
                private route:Router){

  }
  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
    let value:boolean = false;
    if(sessionStorage.getItem("user_session")!=null){
      value = true;
    }
    else{
      this.route.navigate(['login']);
    }
    /*if(this.loginService.isTokenValid().isLoginCorrect){
        value = true;
    }
    else{ this.route.navigate(['']);}
    */
    return value;
    //return true;
  }
  canActivateChild(next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
      debugger;
      let value:boolean = false;
      if(sessionStorage.getItem("user_session")!=null){
        value = true;
      }
      else{
        this.route.navigate(['login']);
      }
      return value;
  }

  
}
