import { NgModule } from '@angular/core';
import { PagesComponent } from './pages/pages.component';
import { SharedModule } from '../shared/shared.module';
import { PAGES_ROUTE } from './pages.route';
import { FormsModule } from '@angular/forms';

import { CommonModule } from '@angular/common';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import { PatientsComponent } from './patients/patients.component';
import { ModalUserComponent } from './modal/user/modal-user/modal-user.component';
import { ModalCredentialUserComponent } from './modal/user/modal-credential-user/modal-credential-user.component';
import { UsersComponent } from './users/users.component';
import { ModalPatientComponent } from './modal/patient/modal-patient/modal-patient.component';
import { ModalAsignOwnerComponent } from './modal/patient/modal-asign-owner/modal-asign-owner.component';
import { ReportsComponent } from './reports/reports.component';
import { NewMedicalAppointmentComponent } from './modal/patient/modal-medical-appointment/modal-medical-appointment.component';
import { ClinicHistoryComponent } from './modal/patient/clinic-history/clinic-history.component';
import {TableModule} from 'primeng/table';
@NgModule({
    declarations: [
        PagesComponent,
        PatientsComponent,
        UsersComponent,
        ModalUserComponent,
        ModalCredentialUserComponent,
        ClinicHistoryComponent,
        ModalPatientComponent,
        ModalAsignOwnerComponent,
        ReportsComponent,
        NewMedicalAppointmentComponent
    ],
    exports: [
        
    ],
    imports: [
        TableModule,
        SharedModule,
        PAGES_ROUTE,
        FormsModule,
        CommonModule,
        NgbModule
    ]
})
export class PagesModule {}
