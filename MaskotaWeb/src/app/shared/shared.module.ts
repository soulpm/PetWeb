import { NgModule } from '@angular/core';
import { SidebarComponent } from './sidebar/sidebar.component';
import { HeaderComponent } from './header/header.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';

@NgModule({
    imports: [
        RouterModule,
        CommonModule
    ],
    declarations: [
        SidebarComponent,
        HeaderComponent,
        PageNotFoundComponent,
    ],
    exports: [
        SidebarComponent,
        HeaderComponent,
        PageNotFoundComponent
    ]
})
export class SharedModule { }
