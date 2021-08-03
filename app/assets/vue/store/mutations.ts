import { AxiosError } from "axios";
import { IEntityState } from "../interfaces/state";
import { IEntity } from "../interfaces/entity";
import { IError } from "../interfaces/error";
import { IListResponse } from "../api";

/**
 * Generic mutations interface.
 */
export interface IMutations {
}


/**
 * Generic mutations interface for regular Read Only operations.
 */
export interface IReadMutations<T extends IEntity = IEntity, S extends IEntityState<T> = IEntityState<T>> extends IMutations {
  GET_ALL_PENDING(state: S): void;
  GET_ALL_SUCCESS(state: S, data: IListResponse<T>): void;
  GET_ALL_ERROR(state: S, error?: AxiosError<IError>): void;

  GET_PENDING(state: S): void;
  GET_SUCCESS(state: S, data: T): void;
  GET_ERROR(state: S, error?: AxiosError<IError>): void;
}


/**
 * Generic state mutations object for regular Read Only operations.
 */
export const ReadMutations: IReadMutations<IEntity, IEntityState<IEntity>> = {
  GET_ALL_PENDING(state: IEntityState<IEntity>): void {
    state.isLoading = true;
    state.clearError();
    state.clearList();
  },
  GET_ALL_SUCCESS(state: IEntityState<IEntity>, data: IListResponse<IEntity>): void {
    state.isLoading = false;
    state.clearError();
    state.items.push(...data.items);
    state.total = data.total;
  },
  GET_ALL_ERROR(state: IEntityState<IEntity>, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  GET_PENDING(state: IEntityState<IEntity>): void {
    state.isLoading = true;
    state.clearError();
  },
  GET_SUCCESS(state: IEntityState<IEntity>, data: IEntity = null): void {
    state.isLoading = false;
    state.clearError();
    state.item = data;
  },
  GET_ERROR(state: IEntityState<IEntity>, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  }

};



/**
 * Generic mutations interface for regular CRUD operations.
 */
export interface IReadWriteMutations<T extends IEntity = IEntity, S extends IEntityState<T> = IEntityState<T>> extends IReadMutations<T, S> {
  CREATE_PENDING(state: S): void;
  CREATE_SUCCESS(state: S, data: T): void;
  CREATE_ERROR(state: S, error?: AxiosError<IError>): void;

  EDIT_PENDING(state: S): void;
  EDIT_SUCCESS(state: S, data: T): void;
  EDIT_ERROR(state: S, error?: AxiosError<IError>): void;

  DELETE_PENDING(state: S): void;
  DELETE_SUCCESS(state: S, entityId: string): void;
  DELETE_ERROR(state: S, error?: AxiosError<IError>): void;
}

/**
 * Generic state mutations object for regular CRUD operations.
 */
export const ReadWriteMutations: IReadWriteMutations<IEntity, IEntityState<IEntity>> = {
  ...ReadMutations,

  CREATE_PENDING(state: IEntityState<IEntity>): void {
    state.isLoading = true;
    state.clearError();
  },
  CREATE_SUCCESS(state: IEntityState<IEntity>, data: IEntity = null): void {
    state.isLoading = false;
    state.clearError();
    state.item = data;
  },
  CREATE_ERROR(state: IEntityState<IEntity>, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  EDIT_PENDING(state: IEntityState<IEntity>): void {
    state.isLoading = true;    state.clearError();

  },
  EDIT_SUCCESS(state: IEntityState<IEntity>, data: IEntity = null): void {
    state.isLoading = false;
    state.clearError();
    state.item = data;
  },
  EDIT_ERROR(state: IEntityState<IEntity>, error?: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  },

  DELETE_PENDING(state: IEntityState<IEntity>): void {
    state.isLoading = true;
    state.clearError();
  },
  DELETE_SUCCESS(state: IEntityState<IEntity>, entityId: string): void {
    state.isLoading = false;
    state.clearError();
    state.items.splice(
      state.items.findIndex((i: IEntity) => {
        return i.id === entityId;
      }),
      1
    );
  },
  DELETE_ERROR(state: IEntityState<IEntity>, error: AxiosError<IError>): void {
    state.isLoading = false;
    state.saveError(error);
  }

};
