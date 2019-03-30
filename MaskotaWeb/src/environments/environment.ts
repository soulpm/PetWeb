// This file can be replaced during build by using the `fileReplacements` array.
// `ng build --prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production              : false                                           ,
  pathImage               : "/assets/images/"                               ,
  initPage                : "/inicio"                                         ,
  urlApiRest              : "http://localhost/maskota-web-service/services" ,
  pathImageLoad           : '/assets/load-icon.gif'                         ,
  _ERROR_ALERT_MESSAGE    : 1                                               ,
  _WARNING_ALERT_MESSAGE  : 2                                               ,
  _SUCCESS_ALERT_MESSAGE  : 3                                               ,
  _INFO_ALERT_MESSAGE     : 4
};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
