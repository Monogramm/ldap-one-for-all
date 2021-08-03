import { AxiosError } from "axios";

import { IError, Error } from "./error";
import { Entity } from "./entity";

import { Api, ReadApi, ReadWriteApi } from "../api";

import { IAuthState, AuthStateDefault } from "../modules/auth/state";

/**
 * Generic store state interface.
 */
export interface IState {
  offline: boolean;
}

/**
 * Generic store state class.
 */
export abstract class AbstractState implements IState {
  offline: boolean = false;
}

/**
 * Generic state for store with API.
 */
export interface IApiState<S extends Api = Api> extends IState {
  api: S;
}


/**
 * Application root store state class.
 */
export interface IRootState extends IState {
  auth: IAuthState;
  // TODO Create a i18n state to manage language
}

/**
 * Application root store state class.
 */
export class RootState extends AbstractState implements IRootState {
  auth: IAuthState = AuthStateDefault;
}



/**
 * Generic entity store state interface.
 */
export interface IEntityState<T extends Entity> extends IState {
  error: IError;
  isLoading: boolean;
  total: number;
  items: Array<T>;
  item: T;

  initList(): Array<T>;
  initCurrent(): T;

  clearList(): void;

  clearError(): void;
  saveError(error: AxiosError<IError>): void;
}

/**
 * Generic entity store state class.
 */
export abstract class AbstractEntityState<T extends Entity> extends AbstractState implements IEntityState<T> {
  error: IError = new Error();
  isLoading: boolean = false;
  total: number = 0;
  items: Array<T> = this.initList();
  item: T = this.initCurrent();

  initList(): Array<T> {
    return [];
  }
  initCurrent(): T {
    return null;
  }

  clearList(): void {
    this.items.splice(0, this.items.length);
  }

  clearError(): void {
    this.error.code = null;
    this.error.status = null;
    this.error.message = null;
  }
  saveError(error: AxiosError<IError>): void {
    if (error && error.response) {
      const response = error.response;
      this.error.status = response.status;

      if (response.data && response.data.code) {
        this.error.code = response.data.code;
      }
      if (response.data && response.data.message) {
        this.error.message = response.data.message;
      } else {
        this.error.message = response.statusText;
      }
    } else {
      this.error.status = 417;
      this.error.message = 'unknown-error';
    }
  }
}

/**
 * Generic state for entity store with API.
 */
export interface IEntityApiState<T extends Entity, S extends Api = Api> extends IEntityState<T>, IApiState<S> {}

/**
 * Generic state for entity store with ReadAPI.
 */
export interface IEntityReadApiState<T extends Entity, S extends ReadApi<T> = ReadApi<T>> extends IEntityApiState<T, S> {}

/**
 * Generic state for entity store with ReadWriteAPI.
 */
export interface IEntityReadWriteApiState<T extends Entity, S extends ReadWriteApi<T> = ReadWriteApi<T>> extends IEntityApiState<T, S> {}
