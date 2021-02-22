import axios from "axios";
import { ReadWriteApi } from "../../api";
import { IParameter } from "./interfaces";

/**
 * Parameter API service.
 */
export class ParameterAPI extends ReadWriteApi<IParameter> {
  private static _instance: ParameterAPI;

  public static get Instance(): ParameterAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("admin/parameter");
  }

  getParameterTypes() {
    // TODO Define an interface or enum for ParameterType
    return axios.get(`${this.base}/admin/parameter/types`)
  }
};
