import { AxiosError, AxiosResponse } from "axios";

import { IReadWriteMutations, ReadWriteMutations } from "../../store/mutations";

import { IUserState } from "./state";
import { IUser, ILogin } from "./interfaces";

export interface IUserMutations extends IReadWriteMutations<IUser, IUserState> {

  PASSWORD_CHANGE_PENDING(state: IUserState): void;
  PASSWORD_CHANGE_SUCCESS(state: IUserState): void;
  PASSWORD_CHANGE_ERROR(state: IUserState, error: AxiosError): void;

  DISABLE_ACCOUNT_SUCCESS(state: IUserState): void;

  VERIFICATION_PENDING(state: IUserState): void;
  VERIFICATION_SUCCESS(state: IUserState): void;
  VERIFICATION_ERROR(state: IUserState, error: AxiosError): void;

  RESEND_CODE_PENDING(state: IUserState): void;
  RESEND_CODE_SUCCESS(state: IUserState): void;
  // TODO RESEND_CODE_ERROR(state: IUserState, error: AxiosError): void;

}

export const UserMutationsDefault: IUserMutations = {
  ...ReadWriteMutations,

  PASSWORD_CHANGE_PENDING(state: IUserState): void {
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  PASSWORD_CHANGE_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  PASSWORD_CHANGE_ERROR(state: IUserState, error: AxiosError): void {
    if (error && error.response) {
      state.error.status = error.response.status;
      state.error.message = error.response.data.message;
      state.error.code = error.response.data.code;
    } else {
      state.error.status = 418;
      state.error.message = 'unknown-error';
    }
  },

  DISABLE_ACCOUNT_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },

  VERIFICATION_PENDING(state: IUserState): void {
    state.isLoading = true;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  VERIFICATION_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  VERIFICATION_ERROR(state: IUserState, error: AxiosError): void {
    state.isLoading = false;
    if (error && error.response) {
      state.error.code = error.response.data.code;
      state.error.message = error.response.data.message;
      state.error.status = error.response.status;
    } else {
      state.error.status = 418;
      state.error.message = 'unknown-error';
    }
  },

  RESEND_CODE_PENDING(state: IUserState): void {
    state.isLoading = true;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },
  RESEND_CODE_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.error.code = null;
    state.error.message = null;
    state.error.status = null;
  },

};
