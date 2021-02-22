import { AxiosError, AxiosResponse } from "axios";

import { IListResponse } from "../../api";
import { IMutations } from "../../store/mutations";

import { IUser } from "../user/interfaces";

import { IAuthState } from "./state";
import { ILogin, ILoginToken } from "./interfaces";

export interface IAuthMutations extends IMutations {
  GET_USER_SUCCESS(state: IAuthState, response: AxiosResponse<IUser>): void;

  LOGIN_PENDING(state: IAuthState): void;
  LOGIN_SUCCESS(state: IAuthState, loginPayload: ILoginToken): void;
  LOGIN_FAILED(state: IAuthState, error: AxiosError): void;

  LOGOUT_PENDING(state: IAuthState): void;
  LOGOUT_SUCCESS(state: IAuthState): void;
  LOGOUT_FAILED(state: IAuthState, error: AxiosError): void;

  CHANGE_LANGUAGE(state: IAuthState, lang: string): void;
}

export const AuthMutationsDefault: IAuthMutations =  {

  GET_USER_SUCCESS(state: IAuthState, response: AxiosResponse<IUser>): void {
    state.isLoading = false;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
    if (response.data) {
      state.authUser = response.data;
    }
  },

  LOGIN_PENDING(state: IAuthState): void {
    state.isLoading = true;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  LOGIN_SUCCESS(state: IAuthState, loginPayload: ILoginToken): void {
    state.isLoading = false;
    state.error.code = null;
    state.error.message = null;
    state.token.update(loginPayload);
    localStorage.setItem("token", loginPayload.token);
  },
  LOGIN_FAILED(state: IAuthState, error: AxiosError): void {
    state.isLoading = false;
    if (error) {
      state.error.status = error.response.status;
    }
    state.token.reset();
    localStorage.removeItem("token");
  },

  LOGOUT_PENDING(state: IAuthState): void {
    state.isLoading = true;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  LOGOUT_SUCCESS(state: IAuthState): void {
    state.isLoading = false;
    state.authUser = null;
    localStorage.removeItem("token");
    if (state.token) {
      state.token.reset();
    }
  },
  LOGOUT_FAILED(state: IAuthState, error: AxiosError): void {
    state.isLoading = false;
    if (error) {
      state.error.status = error.response.status;
    }
    state.authUser = null;
    localStorage.removeItem("token");
    if (state.token) {
      state.token.reset();
    }
  },

  CHANGE_LANGUAGE(state: IAuthState, lang: string): void {
    state.authUser.language = lang;
  },
};
