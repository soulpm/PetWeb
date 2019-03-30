import { IRoleModel } from "../model/login.model";
export interface IUserModel {
    userId?:number;
    role?:IRoleModel;
    typeNif?:IDocumentModel;
    numberNif?:string;
    names?:string;
    email?:string;
    idSystem?:string;
    password?:string;
    image?:string;
    sex?:string;
    address?:string;
    movilNumber?:string;
    landLine?:string;
    estate?:IStateModel;
    tokenSession?:string;
    asignado?:boolean;
}
export interface IDocumentModel {
    idDocument?:number;
    name?:string;
}
export interface IStateModel {
    idState?:number;
    name?:string;
}