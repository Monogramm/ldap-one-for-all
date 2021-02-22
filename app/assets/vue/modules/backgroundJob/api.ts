import axios from "axios";
import { ReadApi } from "../../api";
import { IBackgroundJob } from "./interfaces";

/**
 * BackgroundJob API service.
 */
export class BackgroundJobAPI extends ReadApi<IBackgroundJob> {
  private static _instance: BackgroundJobAPI;

  public static get Instance(): BackgroundJobAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("admin/background-jobs");
  }
};
