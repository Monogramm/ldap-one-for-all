import axios from "axios";

import { IListResponse, ReadWriteApi } from "../../api";
import { ICriteria } from "../../interfaces/criteria";
import { ISort } from "../../interfaces/sort";

import { IUser, IUserVerification, IUserPasswordChange } from "./interfaces";

/**
 * User API service.
 */
export class UserAPI extends ReadWriteApi<IUser> {
  private static _instance: UserAPI;

  public static get Instance(): UserAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("user", "admin/user");
  }

  /**
   * Get all users paginated.
   *
   * @param page page number.
   * @param size page size.
   */
  getAll(page: number, size: number, filters: ICriteria = null, orders: ISort = null) {
    return axios.get<IListResponse<IUser>>(`${this.base}/${this.rwPrefix}`, {
      params: { page: page, size: size, filters: filters, orders: orders },
    });
  }

  /**
   * Get a single user.
   *
   * @param entityId User ID.
   */
  get(userId: string) {
    return axios.get<IUser>(`${this.base}/${this.rwPrefix}/${userId}`);
  }

  // XXX Move to AuthAPI?
  register(user: IUser) {
    return axios.post<string>(`${this.base}/${this.roPrefix}`, user);
  }

  getCurrentUser() {
    return axios.get<IUser>(`${this.base}/${this.roPrefix}`);
  }
  passwordChange(newPassword: IUserPasswordChange) {
    return axios.put<void>(`${this.base}/${this.roPrefix}/password`, newPassword);
  }

  disableAccount() {
    return axios.put<any>(`${this.base}/${this.roPrefix}/disable`);
  }

  confirmCode(code: IUserVerification) {
    return axios.post<void>(`${this.base}/${this.roPrefix}/verify`, { code: code });
  }
  requestCode() {
    return axios.post<void>(`${this.base}/${this.roPrefix}/verify/resend`);
  }

  setPassword(userId: string, newPassword: IUserPasswordChange) {
    return axios.put<void>(`${this.base}/${this.rwPrefix}/${userId}/password`, newPassword);
  }
  setEnable(userId: string, enabled: boolean) {
    return axios.put<any>(`${this.base}/${this.rwPrefix}/${userId}/set-enable`, enabled);
  }
};
