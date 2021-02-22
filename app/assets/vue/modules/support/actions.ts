import { ActionContext } from "vuex";

import { IRootState } from "../../interfaces/state";
import { IActions } from "../../store/actions";

import { ISupportState } from "./state";
import { ISupport } from "./interfaces";

/**
 * User API actions interface.
 */
export interface ISupportActions extends IActions {
  sendRequestEmail({ commit, state }: ActionContext<ISupportState, IRootState>, data: ISupport): Promise<void>;
}

export const SupportActionsDefault: ISupportActions = {
  async sendRequestEmail({ commit, state }: ActionContext<ISupportState, IRootState>, data: ISupport) {
    commit("SUPPORT_SEND_REQUEST_PENDING", data);
    try {
      const response = await state.api.sendRequestEmail(data);
      commit("SUPPORT_SEND_REQUEST_SUCCESS");
    } catch (e) {
      commit("SUPPORT_SEND_REQUEST_ERROR");
    }
  }
};
