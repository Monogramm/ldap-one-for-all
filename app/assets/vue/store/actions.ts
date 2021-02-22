import { ActionContext } from "vuex";
import { AxiosResponse } from "axios";

import { IRootState, IEntityState, IEntityReadApiState, IEntityReadWriteApiState } from "../interfaces/state";
import { IPagination } from "../interfaces/pagination";
import { IEntity } from "../interfaces/entity";
import { Api, IListResponse, ReadApi, ReadWriteApi } from "../api";



/**
 * Generic actions interface.
 */
export interface IActions {
}



/**
 * Read Only actions interface.
 */
export interface IReadActions<T extends IEntity = IEntity, S extends IEntityReadApiState<T> = IEntityReadApiState<T>> extends IActions {
  getAll({ commit, state }: ActionContext<S, IRootState>, { page, size }: IPagination): Promise<any>;
  get({ commit, state }: ActionContext<S, IRootState>, id: string): Promise<any>;
}

/**
 * Generic actions object for Read Only API operations.
 */
export const ReadActions: IReadActions<IEntity, IEntityReadApiState<IEntity>> = {
  async getAll(
    { commit, state }: ActionContext<IEntityReadApiState<IEntity>, IRootState>,
    { page, size }: IPagination = { page: 1, size: -1 }
  ) {
    commit("GET_ALL_PENDING");
    try {
      const response: AxiosResponse<IListResponse<IEntity>> = await state.api.getAll(page, size);
      commit("GET_ALL_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("GET_ALL_ERROR", error);
      return error;
    }
  },

  async get(
    { commit, state }: ActionContext<IEntityReadApiState<IEntity>, IRootState>,
    id: string
  ) {
    commit("GET_PENDING");
    try {
      const response: AxiosResponse<IEntity> = await state.api.get(id);
      commit("GET_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("GET_ERROR", error.response);
      return error;
    }
  },

}



/**
 * CRUD actions interface.
 */
export interface IReadWriteActions<T extends IEntity = IEntity, S extends IEntityReadWriteApiState<T> = IEntityReadWriteApiState<T>> extends IReadActions<T, S> {
  create({ commit, state }: ActionContext<S, IRootState>, entity: T): Promise<any>;
  update({ commit, state }: ActionContext<S, IRootState>, entity: T): Promise<any>;
  delete({ commit, state }: ActionContext<S, IRootState>, id: string): Promise<any>;
}

/**
 * Generic actions object for regular CRUD API operations.
 */
export const ReadWriteActions: IReadWriteActions<IEntity, IEntityReadWriteApiState<IEntity>> = {
  ...ReadActions,

  async create(
    { commit, state }: ActionContext<IEntityReadWriteApiState<IEntity>, IRootState>,
    entity: IEntity
  ) {
    commit("CREATE_PENDING");
    try {
      const response: AxiosResponse<IEntity> = await state.api.create(entity);
      commit("CREATE_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("CREATE_ERROR", error.response);
      return error;
    }
  },

  async update(
    { commit, state }: ActionContext<IEntityReadWriteApiState<IEntity>, IRootState>,
    entity: IEntity
  ) {
    commit("EDIT_PENDING");
    try {
      const response: AxiosResponse<IEntity> = await state.api.update(entity.id, entity);
      commit("EDIT_SUCCESS", response.data);
      return response.data;
    } catch (error) {
      commit("EDIT_ERROR", error.response);
      return error;
    }
  },

  async delete(
    { commit, state }: ActionContext<IEntityReadWriteApiState<IEntity>, IRootState>,
    id: string
  ) {
    commit("DELETE_PENDING");
    try {
      const response: AxiosResponse<void> = await state.api.delete(id);
      commit("DELETE_SUCCESS", id);
      return response.data;
    } catch (error) {
      commit("DELETE_ERROR", error.response);
      return error;
    }
  },

}
