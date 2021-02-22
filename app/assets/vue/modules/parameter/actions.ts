import { ActionContext } from "vuex";

import { IRootState } from "../../interfaces/state";
import { IReadWriteActions, ReadWriteActions } from "../../store/actions";

import { IParameterState } from "./state";
import { IParameter } from "./interfaces";

/**
 * Parameter API actions interface.
 */
export interface IParameterActions extends IReadWriteActions<IParameter, IParameterState> {
  getTypes({ commit, state }: ActionContext<IParameterState, IRootState>): Promise<any>;
}

export const ParameterActionsDefault: IParameterActions = {
  ...ReadWriteActions,

  async getTypes(
    { commit, state }: ActionContext<IParameterState, IRootState>
  ) {
    commit("LOAD_TYPES_PENDING");
    try {
      const response = await state.api.getParameterTypes();
      commit("LOAD_TYPES_SUCCESS");
      return response.data;
    } catch (error) {
      commit("LOAD_TYPES_ERROR");
      return error;
    }
  },
};
