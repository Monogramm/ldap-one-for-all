import { AxiosError, AxiosResponse } from "axios";

import { IError } from "../../interfaces/error";
import { IReadWriteMutations, ReadWriteMutations } from "../../store/mutations";

import { IUserState } from "./state";
import { IUser, ILogin } from "./interfaces";

export interface IUserMutations extends IReadWriteMutations<IUser, IUserState> {

  PASSWORD_CHANGE_PENDING(state: IUserState): void;
  PASSWORD_CHANGE_SUCCESS(state: IUserState): void;
  PASSWORD_CHANGE_ERROR(state: IUserState, error: AxiosError<IError>): void;

  DISABLE_ACCOUNT_SUCCESS(state: IUserState): void;

  VERIFICATION_PENDING(state: IUserState): void;
  VERIFICATION_SUCCESS(state: IUserState): void;
  VERIFICATION_ERROR(state: IUserState, error: AxiosError<IError>): void;

  RESEND_CODE_PENDING(state: IUserState): void;
  RESEND_CODE_SUCCESS(state: IUserState): void;
  // TODO RESEND_CODE_ERROR(state: IUserState, error: AxiosError): void;

  SET_ENABLE_PENDING(state: IUserState): void;
  SET_ENABLE_SUCCESS(state: IUserState): void;
  SET_ENABLE_ERROR(state: IUserState, error: AxiosError<IError>): void;

}

export const UserMutationsDefault: IUserMutations = {
  ...ReadWriteMutations,

  PASSWORD_CHANGE_PENDING(state: IUserState): void {
    state.isLoading = true;
    state.clearError();
  },
  PASSWORD_CHANGE_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.clearError();
  },
  PASSWORD_CHANGE_ERROR(state: IUserState, error: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  DISABLE_ACCOUNT_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.clearError();
  },

  VERIFICATION_PENDING(state: IUserState): void {
    state.isLoading = true;
    state.clearError();
  },
  VERIFICATION_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.clearError();
  },
  VERIFICATION_ERROR(state: IUserState, error: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  RESEND_CODE_PENDING(state: IUserState): void {
    state.isLoading = true;
    state.clearError();
  },
  RESEND_CODE_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.clearError();
  },

  SET_ENABLE_PENDING(state: IUserState): void {
    state.isLoading = true;
    state.clearError();
  },
  SET_ENABLE_SUCCESS(state: IUserState): void {
    state.isLoading = false;
    state.clearError();
  },
  SET_ENABLE_ERROR(state: IUserState, error: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

};
