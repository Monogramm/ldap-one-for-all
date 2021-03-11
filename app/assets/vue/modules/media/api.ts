import axios from "axios";
import { ReadWriteApi } from "../../api";
import { IMedia } from "./interfaces";

/**
 * Media API service.
 */
export class MediaAPI extends ReadWriteApi<IMedia> {
  private static _instance: MediaAPI;

  public static get Instance(): MediaAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("admin/media");
  }

  createMedia(media: IMedia, file: any) {
    let formData = new FormData();
    formData.append("file", file);
    formData.append("dto", JSON.stringify(media));

    return axios.post<IMedia>(`${this.base}/${this.rwPrefix}`, formData);
  }

  updateMedia(media: IMedia, file: any) {
    let formData = new FormData();
    formData.append("file", file);
    formData.append("dto", JSON.stringify(media));

    return axios.post<IMedia>(`${this.base}/${this.rwPrefix}/${media.id}`, formData);
  }
};
