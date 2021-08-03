import { ActionContext } from "vuex";
import { AxiosResponse } from "axios";

import { IRootState } from "../../interfaces/state";
import { IReadWriteActions, ReadWriteActions } from "../../store/actions";

import { ILogin } from "../../modules/auth/interfaces";

import { IUserState } from "./state";
import { IUser, IUserVerification, IUserPasswordChange } from "./interfaces";

/**
 * User API actions interface.
 */
export interface IUserActions extends IReadWriteActions<IUser, IUserState> {
  getAllByUsername({ commit, state }: ActionContext<IUserState, IRootState>, username: string): Promise<any>;
  register({ commit, state }: ActionContext<IUserState, IRootState>, user: IUser): Promise<string>;
  passwordChange({ commit, state }: ActionContext<IUserState, IRootState>, data: IUserPasswordChange): Promise<void>;
  disableAccount({ commit, state }: ActionContext<IUserState, IRootState>): Promise<void>;
  confirmVerificationCode({ commit, state }: ActionContext<IUserState, IRootState>, code: IUserVerification): Promise<AxiosResponse<void>>;
  requestVerificationCode({ commit, state }: ActionContext<IUserState, IRootState>): Promise<void>;
}

export const UserActionsDefault: IUserActions = {
  ...ReadWriteActions,

  async getAllByUsername(
    { commit, state }: ActionContext<IUserState, IRootState>,
    username: string
  ) {
    commit("GET_ALL_PENDING");
    try {
      const response: AxiosResponse<Array<IUser>> = await state.api.getAllByUsername(username);
      commit("GET_ALL_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("GET_ALL_ERROR", error);
      return error;
    }
  },

  async register({ commit, state }: ActionContext<IUserState, IRootState>, user: IUser) {
    commit("CREATE_PENDING");
    try {
      const response = await state.api.register(user);
      commit("CREATE_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("CREATE_ERROR", error);
      return error;
    }
  },

  async passwordChange({ commit, state }: ActionContext<IUserState, IRootState>, data: IUserPasswordChange) {
    commit("PASSWORD_CHANGE_PENDING");
    try {
      await state.api.passwordChange(data);
      commit("PASSWORD_CHANGE_SUCCESS");
    } catch (error) {
      commit("PASSWORD_CHANGE_ERROR", error);
    }
  },
  async disableAccount({ commit, state }: ActionContext<IUserState, IRootState>) {
    await state.api.disableAccount();
    commit("DISABLE_ACCOUNT_SUCCESS");
  },

  async confirmVerificationCode({ commit, state }: ActionContext<IUserState, IRootState>, code: IUserVerification) {
    commit("VERIFICATION_PENDING");
    try {
      const response = await state.api.confirmCode(code);
      commit("VERIFICATION_SUCCESS");
      return response;
    } catch (error) {
      commit("VERIFICATION_ERROR", error);
      return null;
    }
  },
  async requestVerificationCode({ commit, state }: ActionContext<IUserState, IRootState>) {
    commit("RESEND_CODE_PENDING");
    await state.api.requestCode();
    commit("RESEND_CODE_SUCCESS");
  },
};
