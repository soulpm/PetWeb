  export function toInteger(value: any): number {
    return parseInt(`${value}`, 0);
  }
  
  export function toString(value: any): string {
    return (value !== undefined && value !== null) ? `${value}` : '';
  }
  
  export function getValueInRange(value: number, max: number, min = 0): number {
    return Math.max(Math.min(value, max), min);
  }
  
  export function isString(value: any): value is string {
    return typeof value === 'string';
  }
  
  export function isNumber(value: any): value is number {
    return !isNaN(toInteger(value));
  }
  
  export function isInteger(value: any): value is number {
    return typeof value === 'number' && isFinite(value) && Math.floor(value) === value;
  }
  
  export function isDefined(value: any): boolean {
    return value !== undefined && value !== null;
  }
  
  export function padNumber(value: number) {
    if (isNumber(value)) {
      return `0${value}`.slice(-2);
    } else {
      return '';
    }
  }
  
  export function regExpEscape(text) {
    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
  }
  export function createCookie(name, value, days) {
    var expires;
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toDateString();
    } else {
     expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
  }