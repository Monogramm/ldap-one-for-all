/**
 * Error interface.
 */
export interface IError {
  /**
   * Error code.
   */
  code: number;
  /**
   * Error message
   */
  message: string;
  /**
   * HTTP status code.
   */
  status: number;
  /**
   * Detailed errors.
   */
  errors: {[attribute: string]: Array<string>};

  clear(): void;
}

/**
 * Error class.
 */
export class Error implements IError {
  constructor(
    public code: number = null,
    public message: string = null,
    public status: number = null,
    public errors: {[attribute: string]: Array<string>} = {},
  ) {}

  clear(): void {
    this.code = null;
    this.status = null;
    this.message = null;
    this.errors = {};
  }
}
