export interface ILogin {
    userId?: string;
    password?: string;
}

export interface IUserAccount {
    userId?:number;
    role?:IRoleModel;
    names?:string;
    email?:string;
    typeNif?:string;
    nif?:string;
    photo?:string;
    state?:string;
    tokenSession?:string;
}
export interface IRoleModel{
    idRole?:number
    name?:string;
    state?:string;
}
export interface ILoginResponse{
    stateLogin?:number;
    isLoginCorrect?:boolean;
    isFailure?:boolean;
    showMessage?:boolean;
    typeMessage?:string;
    titleMessage?:string;
    message?:string;
    userAccount?:IUserAccount;     
}
export interface ISexModel{
    idRegister?:string;
    name?:string; 
}