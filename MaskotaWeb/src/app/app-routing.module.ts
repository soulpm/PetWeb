import { NgModule } from '@angular/core';
import { Routes, RouterModule, Route } from '@angular/router';
import { AuthGuard}           from './auth.guard';
import { LoginComponent }     from './component/login/login.component';
import { PageNotFoundComponent } from './shared/page-not-found/page-not-found.component';

const routerPaths:Route[] = [
  { path : ''       ,component:LoginComponent           },
  { path : 'login'  ,component:LoginComponent           },
  { path : '**'     , component: PageNotFoundComponent  }
];
@NgModule({
  imports: [RouterModule.forRoot(routerPaths)],
  exports: [RouterModule],
  providers: [AuthGuard], 
})
export class AppRoutingModule{}


