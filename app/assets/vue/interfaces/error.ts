/**
 * Error interface.
 */
export interface IError {
  code: number;
  message: string;
  status: number;
}

/**
 * Error class.
 */
export class Error implements IError {
  code: number = null;
  message: string = null;
  status: number = null;
}
