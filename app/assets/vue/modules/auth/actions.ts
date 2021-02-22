import { ActionContext } from "vuex";
import { AxiosResponse } from "axios";

import { IRootState } from "../../interfaces/state";
import { IActions } from "../../store/actions";

import { UserAPI } from "../user/api";
import { IUser } from "../user/interfaces";

import { IAuthState } from "./state";
import { ILogin } from "./interfaces";

/**
 * Authentication API actions interface.
 */
export interface IAuthActions extends IActions {
  login({ commit, state }: ActionContext<IAuthState, IRootState>, credentials: ILogin): Promise<any>;
  logout({ commit, state }: ActionContext<IAuthState, IRootState>): Promise<void>;
  getAuthUser({ commit, state }: ActionContext<IAuthState, IRootState>): Promise<AxiosResponse<IUser>>;
  // TODO Move to a i18n module?
  changeLanguage({ commit, state }: ActionContext<IAuthState, IRootState>, lang: string): Promise<void>;
}

export const AuthActionsDefault: IAuthActions = {
  async login({ commit, state }: ActionContext<IAuthState, IRootState>, credentials: ILogin) {
    commit(`LOGIN_PENDING`);
    try {
      const responseLdap = await state.api.loginLdap(credentials);
      commit(`LOGIN_SUCCESS`, responseLdap.data);
      return responseLdap;
    } catch (errorLdap) {
      try {
        const response = await state.api.login(credentials);
        commit(`LOGIN_SUCCESS`, response.data);
        return response;
      } catch (error) {
        commit(`LOGIN_FAILED`, errorLdap);
        commit(`LOGIN_FAILED`, error);
        return null;
      }
    }
  },
  async logout({ commit, state }: ActionContext<IAuthState, IRootState>) {
    try {
      commit(`LOGOUT_PENDING`);
      await state.api.logout(state.token);
      commit(`LOGOUT_SUCCESS`);
      return null;
    } catch (e) {
      commit(`LOGOUT_FAILED`);
      return null;
    }
  },

  async getAuthUser({ commit, state }: ActionContext<IAuthState, IRootState>) {
    try {
      const response = await UserAPI.Instance.getCurrentUser();
      commit(`GET_USER_SUCCESS`, response);
      return response;
    } catch (e) {
      commit(`LOGOUT_SUCCESS`);
      return null;
    }
  },

  async changeLanguage({ commit, state }: ActionContext<IAuthState, IRootState>, lang: string) {
    commit(`CHANGE_LANGUAGE`, lang);
  },
};
