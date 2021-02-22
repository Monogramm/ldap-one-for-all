import { AxiosError } from "axios";

import { IListResponse } from "../../api";
import { IReadWriteMutations, ReadWriteMutations } from "../../store/mutations";

import { IParameterState } from "./state";
import { IParameter } from "./interfaces";

export interface IParameterMutations extends IReadWriteMutations<IParameter, IParameterState> {

  LOAD_TYPES_PENDING(state: IParameterState): void;
  LOAD_TYPES_SUCCESS(state: IParameterState): void;
  LOAD_TYPES_ERROR(state: IParameterState): void;

}

export const ParameterMutationsDefault: IParameterMutations = {
  ...ReadWriteMutations,

  LOAD_TYPES_PENDING(state: IParameterState) {
    state.isLoading = true;
  },
  LOAD_TYPES_SUCCESS(state: IParameterState) {
    state.isLoading = false;
  },
  LOAD_TYPES_ERROR(state: IParameterState) {
    state.isLoading = false;
  },

};
