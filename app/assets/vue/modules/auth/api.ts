import axios from "axios";
import { AbstractApi } from "../../api";
import { ILogin, ILoginToken } from "./interfaces";

/**
 * Authentication API service.
 */
export class AuthAPI extends AbstractApi {
  private static _instance: AuthAPI;

  public static get Instance(): AuthAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor(typePrefix:string = '', base: string = '/api') {
    super(typePrefix, base);
  }

  /**
   * LDAP Login.
   *
   * @param entity Login credentials.
   */
  loginLdap(entity: ILogin) {
    return axios.post<ILoginToken>(`${this.base}/login/ldap`, entity);
  }

  /**
   * Login.
   *
   * @param entity Login credentials.
   */
  login(entity: ILogin) {
    return axios.post<ILoginToken>(`${this.base}/login`, entity);
  }

  /**
   * Logout.
   *
   * @param entity Login information to invalidate on backend.
   */
  logout(entity: ILoginToken = null) {
    return axios.post<void>(`${this.base}/logout`, entity);
  }
};
