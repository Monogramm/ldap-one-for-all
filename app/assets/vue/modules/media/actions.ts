import { ActionContext } from "vuex";
import { AxiosResponse } from "axios";

import { IRootState } from "../../interfaces/state";
import { IReadWriteActions, ReadWriteActions } from "../../store/actions";

import { IMediaState } from "./state";
import { IMedia } from "./interfaces";

export interface IMediaPayload {
  media: IMedia;
  file: any;
}

/**
 * Media API actions interface.
 */
export interface IMediaActions extends IReadWriteActions<IMedia, IMediaState> {
  createMedia({ commit, state }: ActionContext<IMediaState, IRootState>, { media, file }: IMediaPayload): Promise<any>;
  updateMedia({ commit, state }: ActionContext<IMediaState, IRootState>, { media, file }: IMediaPayload): Promise<any>;
}

export const MediaActionsDefault: IMediaActions = {
  ...ReadWriteActions,

  async createMedia(
    { commit, state }: ActionContext<IMediaState, IRootState>,
    { media, file }: IMediaPayload
  ) {
    commit("CREATE_PENDING");
    try {
      const response: AxiosResponse<IMedia> = await state.api.createMedia(media, file);
      commit("CREATE_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("CREATE_ERROR", error.response);
      return error;
    }
  },

  async updateMedia(
    { commit, state }: ActionContext<IMediaState, IRootState>,
    { media, file }: IMediaPayload
  ) {
    commit("EDIT_PENDING", media);
    try {
      const response: AxiosResponse<IMedia> = await state.api.updateMedia(media, file);
      commit("EDIT_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("EDIT_ERROR", error.response);
      return error;
    }
  },

};
