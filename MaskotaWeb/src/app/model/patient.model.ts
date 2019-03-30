import { IDocumentModel, IUserModel } from "./user.model";

export interface IPatientModel{
    idPatient?:number;
    kindPatient?:IKindPatientModel;
    names?:string;
    sex?:ISexPatientModel;
    breed?:string;
    color?:string;
    dateBorn?:any;
    year?:number;
    month?:number;
}
export interface ISexPatientModel{
    idSex?:number;
    name?:string;
} 
export interface IKindPatientModel {
    idKind?:number;
    name?:string;
}
export interface IHistoryClinic{
    idHistory?:number;
    patient?:IPatientModel;
    idClinicSign?:string;
    clinicSign?:string;
    medic?:IUserModel;
    dateRegister?:string;
    stature?:number;
    temperature?:number;
    weight?:number;
    diagnostic?:string;
    recommend?:string;
    nextDate?:string;
    treatment?:string;
    vaccine?:string;
    chemotherapy?:string;
    payment?:number;
    vaccineCompleted?:any;
    desparacitado?:any;
    withOperation?:any;
    socialKnown?:any;
    itvePulgas?:any;
    itveGarrapata?:any;
    itveHongos?:any;
    itveOtitis?:any;
    itveBanioStandar?:any;
    itveBanioMedicado?:any;
    itvePromoGratis?:any;
    itveCorte?:any;
}
export interface IMedic{
    idMedic?:number;
    names?:string;
}
export interface IClinicSigns{
    idClinicSign?:number;
    name?:string;
    isChecked?:boolean;
}
export interface IUserPatientModel {
    userId?:number;
    typeNif?:IDocumentModel;
    numberNif?:string;
    names?:string;
    email?:string;
    image?:string;
    tokenSession?:string;
    isOwner?:number;
}



