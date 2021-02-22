import axios from "axios";
import { Api } from "../../api";
import { ISupport } from "./interfaces";

/**
 * Support API service.
 */
export class SupportAPI implements Api {
  private static _instance: SupportAPI;

  public static get Instance(): SupportAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
  }

  sendRequestEmail(data: ISupport) {
    return axios.post<void>('/api/support/email/send', data);
  }
};
