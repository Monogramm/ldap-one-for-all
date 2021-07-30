import { IError, Error } from "../../interfaces/error";
import { IApiState, AbstractState } from "../../interfaces/state";

import { IUser, User } from "../user/interfaces";

import { ILoginToken, LoginToken } from "./interfaces";
import { AuthAPI } from "./api";

/**
 * Authentication store state interface.
 */
export interface IAuthState extends IApiState<AuthAPI> {
  error: IError;
  isLoading: boolean;

  token: ILoginToken;

  authUser: IUser;
  isLoggedIn(): boolean;
  hasRole(role: string): boolean;
}

/**
 * Authentication store state class.
 */
export class AuthState extends AbstractState implements IAuthState {
  api = AuthAPI.Instance;

  error: IError = new Error();
  isLoading: boolean = false;

  token: ILoginToken = new LoginToken();

  authUser: IUser = null;

  isLoggedIn(): boolean {
    return !!this.authUser;
  }

  hasRole(role: string): boolean {
    return this.isLoggedIn() && this.authUser.roles.includes("ROLE_" + role);
  }
}

/**
 * Factory to generate new default Authentication store state class.
 */
export const AuthStateDefault = new AuthState();
