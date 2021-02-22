import { IError } from "../interfaces/error";
import { IEntityState } from "..//interfaces/state";
import { IEntity } from "..//interfaces/entity";

/**
 * Generic getters interface.
 */
export interface IGetters<T extends IEntity = IEntity, S extends IEntityState<T> = IEntityState<T>> {
  isLoading(state: S): boolean;
  hasError(state: S): boolean;
  error(state: S): IError;
  items(state: S): Array<T>;
  item(state: S): T;
  total(state: S): number;
}

/**
 * Generic getters object.
 */
export const BaseGetters: IGetters<IEntity, IEntityState<IEntity>> =  {
  isLoading(state: IEntityState<IEntity>): boolean {
    return state.isLoading;
  },
  hasError(state: IEntityState<IEntity>): boolean {
    return state.error.status !== null;
  },
  error(state: IEntityState<IEntity>): IError {
    return state.error;
  },
  items(state: IEntityState<IEntity>): Array<IEntity> {
    return state.items;
  },
  item(state: IEntityState<IEntity>): IEntity {
    return state.item;
  },
  total(state: IEntityState<IEntity>): number {
    return state.total;
  }
}
