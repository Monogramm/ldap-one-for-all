
/**
 * Login interface.
 */
export interface ILogin {
  username: string;
  password: string;
}

/**
 * Login class.
 */
export class Login implements ILogin {
  username: string;
  password: string;

  constructor(username: string = "", password: string = "") {
    this.username = username;
    this.password = password;
  }
}

/**
 * Factory to generate new default Login class.
 */
export const LoginDefault = (): Login => {
  return new Login();
};



/**
 * Login token interface.
 */
export interface ILoginToken {
  token: string;
  tokenExpiration: Date;
  refreshToken: string;
  refreshTokenExpiration: Date;

  /**
   * Reset login token.
   */
  reset(): void;

  /**
   * Update current token from a login token payload.
   */
  update(newToken: ILoginToken): void;

  /**
   * Check if token is empty (not authenticated).
   */
  isEmpty(): boolean;
}

/**
 * Login token class.
 */
export class LoginToken implements ILoginToken {
  token: string = null;
  tokenExpiration: Date = new Date();
  refreshToken: string = null;
  refreshTokenExpiration: Date = new Date();

  constructor(token: string = "",
    tokenExpiration: string | number | Date = "",
    refreshToken: string = "",
    refreshTokenExpiration: string | number | Date = "") {

    this.token = token;
    this.tokenExpiration = new Date(tokenExpiration);
    this.refreshToken = refreshToken;
    this.refreshTokenExpiration = new Date(refreshTokenExpiration);
  }

  reset(): void {
    this.token = null;
    this.tokenExpiration = new Date();
    this.refreshToken = null;
    this.refreshTokenExpiration = new Date();
  }

  update(newToken: ILoginToken): void {
    this.token = newToken.token;
    this.tokenExpiration = new Date(newToken.tokenExpiration);
    this.refreshToken = newToken.refreshToken;
    this.refreshTokenExpiration = new Date(newToken.refreshTokenExpiration);
  }

  isEmpty(): boolean {
    return !this.token;
  }
}
