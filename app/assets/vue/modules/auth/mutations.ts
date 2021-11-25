import { AxiosError, AxiosResponse } from "axios";

import { IListResponse } from "../../api";
import { IMutations } from "../../store/mutations";

import { IUser } from "../user/interfaces";

import { IAuthState } from "./state";
import { ILogin, ILoginToken } from "./interfaces";

export interface IAuthMutations extends IMutations {
  GET_USER_PENDING(state: IAuthState): void;
  GET_USER_SUCCESS(state: IAuthState, response: AxiosResponse<IUser>): void;
  GET_USER_FAILED(state: IAuthState, error: AxiosError): void;

  LOGIN_PENDING(state: IAuthState): void;
  LOGIN_SUCCESS(state: IAuthState, loginPayload: ILoginToken): void;
  LOGIN_FAILED(state: IAuthState, error: AxiosError): void;

  LOGOUT_PENDING(state: IAuthState): void;
  LOGOUT_SUCCESS(state: IAuthState): void;
  LOGOUT_FAILED(state: IAuthState, error: AxiosError): void;

  START_IMPERSONATION_PENDING(state: IAuthState): void;
  START_IMPERSONATION_SUCCESS(state: IAuthState, user: string): void;
  START_IMPERSONATION_FAILED(state: IAuthState, error: AxiosError): void;

  STOP_IMPERSONATION_PENDING(state: IAuthState): void;
  STOP_IMPERSONATION_SUCCESS(state: IAuthState): void;
  STOP_IMPERSONATION_FAILED(state: IAuthState, error: AxiosError): void;

  CHANGE_LANGUAGE(state: IAuthState, lang: string): void;
}

export const AuthMutationsDefault: IAuthMutations =  {

  GET_USER_PENDING(state: IAuthState): void {
    state.isLoading = true;
    state.error.clear();
  },
  GET_USER_SUCCESS(state: IAuthState, response: AxiosResponse<IUser>): void {
    state.isLoading = false;
    state.error.clear();
    if (response.data) {
      state.authUser = response.data;
    }
  },
  GET_USER_FAILED(state: IAuthState, error: AxiosError): void {
    state.isLoading = false;
    if (error) {
      state.error.status = error.response.status;
    }
  },

  LOGIN_PENDING(state: IAuthState): void {
    state.isLoading = true;
    state.error.clear();
  },
  LOGIN_SUCCESS(state: IAuthState, loginPayload: ILoginToken): void {
    state.isLoading = false;
    state.error.clear();
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
    state.error.clear();
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

  START_IMPERSONATION_PENDING(state: IAuthState): void {
    state.isLoading = true;
    state.error.clear();
  },
  START_IMPERSONATION_SUCCESS(state: IAuthState, username: string): void {
    state.isLoading = false;
    state.error.clear();
    state.authUser = null;
    state.impersonate = username;
    localStorage.setItem("impersonate", username);
  },
  START_IMPERSONATION_FAILED(state: IAuthState, error: AxiosError): void {
    state.isLoading = false;
    if (error) {
      state.error.status = error.response.status;
    }
    state.impersonate = null;
    localStorage.removeItem("impersonate");
  },

  STOP_IMPERSONATION_PENDING(state: IAuthState): void {
    state.isLoading = true;
    state.error.clear();
  },
  STOP_IMPERSONATION_SUCCESS(state: IAuthState): void {
    state.isLoading = false;
    state.authUser = null;
    localStorage.removeItem("impersonate");
    if (state.impersonate) {
      state.impersonate = null;
    }
  },
  STOP_IMPERSONATION_FAILED(state: IAuthState, error: AxiosError): void {
    state.isLoading = false;
    if (error) {
      state.error.status = error.response.status;
    }
    localStorage.removeItem("impersonate");
    if (state.impersonate) {
      state.impersonate = '_exit';
    }
  },

  CHANGE_LANGUAGE(state: IAuthState, lang: string): void {
    if (state.authUser) {
      state.authUser.language = lang;
    }
  },
};
