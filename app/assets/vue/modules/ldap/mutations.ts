import { AxiosError } from "axios";

import { IListResponse } from "../../api";
import { IError } from "../../interfaces/error";

import { ILdapEntryState } from "./state";
import { ILdapEntry } from "./interfaces";

export interface ILdapEntryMutations {
  GET_ALL_PENDING(state: ILdapEntryState): void;
  GET_ALL_SUCCESS(state: ILdapEntryState, data: IListResponse<ILdapEntry>): void;
  GET_ALL_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void;

  GET_PENDING(state: ILdapEntryState): void;
  GET_SUCCESS(state: ILdapEntryState, data: ILdapEntry): void;
  GET_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void;

  CREATE_PENDING(state: ILdapEntryState): void;
  CREATE_SUCCESS(state: ILdapEntryState, data: ILdapEntry): void;
  CREATE_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void;

  EDIT_PENDING(state: ILdapEntryState): void;
  EDIT_SUCCESS(state: ILdapEntryState, data: ILdapEntry): void;
  EDIT_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void;

  DELETE_PENDING(state: ILdapEntryState): void;
  DELETE_SUCCESS(state: ILdapEntryState, entryDn: string): void;
  DELETE_ERROR(state: ILdapEntryState, response: any): void;
}

export const LdapEntryMutationsDefault: ILdapEntryMutations = {
  GET_ALL_PENDING(state: ILdapEntryState): void {
    state.isLoading = true;
    state.clearError();
    state.clearList();
  },
  GET_ALL_SUCCESS(state: ILdapEntryState, data: IListResponse<ILdapEntry>): void {
    state.isLoading = false;
    state.clearError();
    state.items.push(...data.items);
    state.total = data.total;
  },
  GET_ALL_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  GET_PENDING(state: ILdapEntryState): void {
    state.isLoading = true;
    state.clearError();
  },
  GET_SUCCESS(state: ILdapEntryState, data: ILdapEntry = null): void {
    state.isLoading = false;
    state.clearError();
    state.item = data;
  },
  GET_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  CREATE_PENDING(state: ILdapEntryState): void {
    state.isLoading = true;
    state.clearError();
  },
  CREATE_SUCCESS(state: ILdapEntryState, data: ILdapEntry = null): void {
    state.isLoading = false;
    state.clearError();
    state.item = data;
  },
  CREATE_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  EDIT_PENDING(state: ILdapEntryState): void {
    state.isLoading = true;
    state.clearError();
  },
  EDIT_SUCCESS(state: ILdapEntryState, data: ILdapEntry = null): void {
    state.isLoading = false;
    state.clearError();
    state.item = data;
  },
  EDIT_ERROR(state: ILdapEntryState, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  DELETE_PENDING(state: ILdapEntryState): void {
    state.isLoading = true;
    state.clearError();
  },
  DELETE_SUCCESS(state: ILdapEntryState, entryDn: string): void {
    state.isLoading = false;
    state.clearError();
    state.items.splice(
      state.items.findIndex((i: ILdapEntry) => {
        return i.dn === entryDn;
      }),
      1
    );
  },
  DELETE_ERROR(state: ILdapEntryState, error: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

};
