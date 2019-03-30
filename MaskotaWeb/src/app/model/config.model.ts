export interface IMenu{
    idMenu          ?: number;
    nameOption      ?: string;
    urlLink         ?: string;
    rolePermission  ?: number;
    iconOption      ?: string;
    active          ?: string;
}
export interface IMessageModel{
    typeMessage?:string;
    showMessage?:boolean;
    titleMessage?:string;
    showLoad?:boolean;
    message?:string;  
}
export interface IResultResponse{
    responseCode?:number;
    data?:any;
}
export interface IColumnTable{
    nameColumn?:string;
    iconColumn?:string;
    class?:string;
}

