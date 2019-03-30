import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { IPatientModel, IUserPatientModel, IClinicSigns, IHistoryClinic } from '../model/patient.model';
import { ConfigurationService } from './configuration.service';
import { HttpHeaders, HttpParams, HttpClient } from '@angular/common/http';
import { IResultResponse } from '../model/config.model';
import { IUserModel } from '../model/user.model';

@Injectable({
  providedIn: 'root'
})
export class PatientService {

  private UrlListBase           = environment.urlApiRest+"/ws_patient.php";
  
  constructor(private http:HttpClient,private configService:ConfigurationService) { 

  }
  
    getListPatients(patientEntity:IPatientModel,tokenSession:string):any{
      return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id_patient', patientEntity.idPatient.toString());
            parameters = parameters.set('id_kind', patientEntity.kindPatient.idKind.toString());
            parameters = parameters.set('name',patientEntity.names);
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listPatient:IPatientModel[] = obj.data;
                      resolve(listPatient); 
                },
                error => {
                  reject(error);
                }
            )
      });
    }
    getSignClinic(id:number,name:string,tokenSession:string):any{
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_sign_clinic");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id', id.toString());
            parameters = parameters.set('name',name);
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                let obj:IResultResponse = response;
                let listClinicSigns:IClinicSigns[] = obj.data;
                    resolve(listClinicSigns); 
                },
                error => {
                reject(error);
                }
            )
    });
    }
    getDiagnostics(id:number,name:string,tokenSession:string):any{
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_diagnostic");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id', id.toString());
            parameters = parameters.set('name',name);
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                let obj:IResultResponse = response;
                let listClinicSigns:IClinicSigns[] = obj.data;
                    resolve(listClinicSigns); 
                },
                error => {
                reject(error);
                }
            )
    });
    }
    getVaccines(id:number,name:string,tokenSession:string):any{
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_vaccine");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id', id.toString());
            parameters = parameters.set('name',name);
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                let obj:IResultResponse = response;
                let listClinicSigns:IClinicSigns[] = obj.data;
                    resolve(listClinicSigns); 
                },
                error => {
                reject(error);
                }
            )
    });
    }
    getTreatments(id:number,name:string,diagnostic:number,tokenSession:string):any{
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "list_treatments");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id', id.toString());
            parameters = parameters.set('name',name);
            parameters = parameters.set('diagnostic',diagnostic.toString());
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                let obj:IResultResponse = response;
                let listClinicSigns:IClinicSigns[] = obj.data;
                    resolve(listClinicSigns); 
                },
                error => {
                reject(error);
                }
            )
    });
    }
    createPatient(patientEntity:IPatientModel,tokenSession:string){
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "create");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('kind', patientEntity.kindPatient.idKind.toString());
            parameters = parameters.set('names',patientEntity.names);
            parameters = parameters.set('sex',patientEntity.sex.idSex.toString());
            parameters = parameters.set('breed',patientEntity.breed);
            parameters = parameters.set('color',patientEntity.color);
            parameters = parameters.set('date_born',patientEntity.dateBorn);
            parameters = parameters.set('year',patientEntity.year.toString());
            parameters = parameters.set('month',patientEntity.month.toString());
            
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let message:string = obj.data;
                      resolve(message); 
                },
                error => {
                  reject(error);
                }
            )
        });
    }
    editPatient(patientEntity:IPatientModel,tokenSession:string){
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "edit");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id_patient', patientEntity.idPatient.toString());
            parameters = parameters.set('kind', patientEntity.kindPatient.idKind.toString());
            parameters = parameters.set('names',patientEntity.names);
            parameters = parameters.set('sex',patientEntity.sex.idSex.toString());
            parameters = parameters.set('breed',patientEntity.breed);
            parameters = parameters.set('color',patientEntity.color);
            parameters = parameters.set('date_born',patientEntity.dateBorn);
            parameters = parameters.set('year',patientEntity.year.toString());
            parameters = parameters.set('month',patientEntity.month.toString());
            
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let listPatient:IPatientModel[] = obj.data;
                      resolve(listPatient); 
                },
                error => {
                  reject(error);
                }
            )
        });
    }
    createPatientOwners(users:string,userDrop:string,patient:number,tokenSession:string){
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "create_owners");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('users',users);
            parameters = parameters.set('user_drop',userDrop);
            parameters = parameters.set('patient', patient.toString());
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let message:string = obj.data;
                      resolve(message); 
                },
                error => {
                  reject(error);
                }
            )
        });
    }
    createAttention(historyEntity:IHistoryClinic,tokenSession:string){
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "create_attention");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('patient',historyEntity.patient.idPatient.toString());
            parameters = parameters.set('sign_clinic',historyEntity.clinicSign);
            parameters = parameters.set('medic',historyEntity.medic.userId.toString());
            parameters = parameters.set('date_register',historyEntity.dateRegister);
            parameters = parameters.set('stature',historyEntity.stature.toString());
            parameters = parameters.set('weight',historyEntity.weight.toString());
            parameters = parameters.set('temperature',historyEntity.temperature.toString());
            parameters = parameters.set('recommend',historyEntity.recommend);
            parameters = parameters.set('diagnostic',historyEntity.diagnostic);
            parameters = parameters.set('next_date',historyEntity.nextDate);
            parameters = parameters.set('treatment',historyEntity.treatment);
            parameters = parameters.set('vaccine',historyEntity.vaccine);
            parameters = parameters.set('chemotherapy',historyEntity.chemotherapy);
            parameters = parameters.set('payment',historyEntity.payment.toString());
            parameters = parameters.set('vaccine_completed',historyEntity.vaccineCompleted.toString());
            parameters = parameters.set('desparacitado',historyEntity.desparacitado.toString());
            parameters = parameters.set('with_operation',historyEntity.withOperation.toString());
            parameters = parameters.set('social_known',historyEntity.socialKnown.toString());
            parameters = parameters.set('itve_pulgas',historyEntity.itvePulgas.toString());
            parameters = parameters.set('itve_garrapata',historyEntity.itveGarrapata.toString());
            parameters = parameters.set('itve_hongos',historyEntity.itveHongos.toString());
            parameters = parameters.set('itve_otitis',historyEntity.itveOtitis.toString());
            parameters = parameters.set('itve_banio_std',historyEntity.itveBanioStandar.toString());
            parameters = parameters.set('itve_banio_med',historyEntity.itveBanioMedicado.toString());
            parameters = parameters.set('itve_corte',historyEntity.itveCorte.toString());
            parameters = parameters.set('itve_promo_success',historyEntity.itvePromoGratis.toString());
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let message:string = obj.data;
                      resolve(message); 
                },
                error => {
                  reject(error);
                }
            )
        });
    }
    editAttention(historyEntity:IHistoryClinic,tokenSession:string){
        return new Promise((resolve, reject) => {
            let obj     = this;
            let header  = obj.configService.getHeader();
            let parameters = new HttpParams();
            parameters = parameters.set('type', "create_attention");
            parameters = parameters.set('token_session', tokenSession);
            parameters = parameters.set('id_history',historyEntity.idHistory.toString());
            parameters = parameters.set('patient',historyEntity.patient.idPatient.toString());
            parameters = parameters.set('sign_clinic',historyEntity.clinicSign);
            parameters = parameters.set('medic',historyEntity.medic.userId.toString());
            parameters = parameters.set('date_register',historyEntity.dateRegister);
            parameters = parameters.set('stature',historyEntity.stature.toString());
            parameters = parameters.set('weight',historyEntity.weight.toString());
            parameters = parameters.set('diagnostic',historyEntity.diagnostic);
            parameters = parameters.set('next_date',historyEntity.nextDate);
            parameters = parameters.set('treatment',historyEntity.treatment);
            parameters = parameters.set('vaccine',historyEntity.vaccine);
            parameters = parameters.set('chemotherapy',historyEntity.chemotherapy);
            parameters = parameters.set('payment',historyEntity.payment.toString());
            this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                response => {
                  let obj:IResultResponse = response;
                  let message:string = obj.data;
                      resolve(message); 
                },
                error => {
                  reject(error);
                }
            )
        });
    }
    getListAttention(idHistory:number,idPatient:number,tokenSession:string):any{
        return new Promise((resolve, reject) => {
              let obj     = this;
              let header  = obj.configService.getHeader();
              let parameters = new HttpParams();
              parameters = parameters.set('type', "list_attention");
              parameters = parameters.set('token_session', tokenSession);
              parameters = parameters.set('id_history',idHistory.toString());
              parameters = parameters.set('id_patient', idPatient.toString());
              this.http.post(this.UrlListBase,parameters,{headers:header}).subscribe(
                  response => {
                    let obj:IResultResponse = response;
                    let listHistory:IHistoryClinic[] = obj.data;
                        resolve(listHistory); 
                  },
                  error => {
                    reject(error);
                  }
              )
        });
    }    
        
  
  

}
