import { Routes, RouterModule } from '@angular/router';
import { Component } from '@angular/core';
import { PagesComponent } from './pages/pages.component';
import { PatientsComponent } from './patients/patients.component';
import { AuthGuard } from '../auth.guard';
import { LoginComponent } from './login/login.component';
import { UsersComponent } from './users/users.component';
import { ReportsComponent } from './reports/reports.component';
import { InicioComponent } from './inicio/inicio.component';

const pagesRoutes: Routes = [
    { path : 'login'  ,component:LoginComponent           },
    {   path: '',
        canActivate: [AuthGuard],
        component: PagesComponent,
        children: [
            { path: 'inicio'            ,  component: InicioComponent,canActivateChild:[AuthGuard]},
            { path: 'patient'           ,  component: PatientsComponent,canActivateChild:[AuthGuard]},
            { path: 'users'             ,  component: UsersComponent,canActivateChild:[AuthGuard]},
            { path: 'reports'           ,  component: ReportsComponent,canActivateChild:[AuthGuard]},
            
        ] 
    },
    
    
];
export const PAGES_ROUTE = RouterModule.forChild( pagesRoutes );
