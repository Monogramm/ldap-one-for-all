import axios from "axios";

import { IListResponse, ReadWriteApi } from "../../api";

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
   * Get all items paginated.
   *
   * @param page page number.
   * @param size page size.
   */
  getAll(page: number, size: number) {
    // TODO Make getAll work without pagination to retrieve all (with size = -1)
    // TODO Rename items to size
    return axios.get<IListResponse<IUser>>(`${this.base}/${this.rwPrefix}`, {
      params: { page: page, items: size },
    });
  }

  // XXX Move to AuthAPI?
  register(user: IUser) {
    return axios.post<string>(`${this.base}/${this.roPrefix}`, user);
  }

  getAllByUsername(username: string) {
    return axios.get<Array<IUser>>(`${this.base}/admin/users`, { params: { username: username } });
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
};
