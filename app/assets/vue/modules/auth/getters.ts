import { IError } from "../../interfaces/error";
import { IGetters } from "../../store/getters";

import { IUser, User } from "../user/interfaces";

import { ILoginToken } from "./interfaces";
import { IAuthState } from "./state";

export interface IAuthGetters {
  isLoading(state: IAuthState): boolean;
  hasError(state: IAuthState): boolean;
  error(state: IAuthState): IError;
  token(state: IAuthState): ILoginToken;
  authUser(state: IAuthState): IUser;
  isLoggedIn(state: IAuthState): boolean;
  isAdmin(state: IAuthState): boolean;
  isImpersonator(state: IAuthState): boolean;
  impersonating(state: IAuthState): string;
  language(state: IAuthState): string;
}

export const AuthGettersDefault: IAuthGetters = {
  isLoading(state: IAuthState): boolean {
    return state.isLoading;
  },
  hasError(state: IAuthState): boolean {
    return state.error.status !== null;
  },
  error(state: IAuthState): IError {
    return state.error;
  },
  token(state: IAuthState): ILoginToken {
    return state.token;
  },
  authUser(state: IAuthState): IUser {
    return state.authUser;
  },
  isLoggedIn(state: IAuthState): boolean {
    return state.isLoggedIn();
  },
  isAdmin(state: IAuthState): boolean {
    return state.hasRole("ADMIN");
  },
  isImpersonator(state: IAuthState): boolean {
    return state.isImpersonator();
  },
  impersonating(state: IAuthState): string {
    return state.impersonate;
  },
  language(state: IAuthState): string {
    // TODO Get language from browser
    return state.authUser ? state.authUser.language : User.DEFAULT_LANGUAGE;
  },
}
