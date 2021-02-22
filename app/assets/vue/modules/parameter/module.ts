import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { IParameter } from "./interfaces";

import { IParameterState, ParameterStateDefault } from "./state";
import { ParameterGettersDefault } from "./getters";
import { ParameterMutationsDefault } from "./mutations";
import { ParameterActionsDefault } from "./actions";

export const ParameterModule: Module<IParameterState, IRootState> = {
  namespaced: true,
  state: ParameterStateDefault,
  getters: {
    ...ParameterGettersDefault,
  },
  mutations: {
    ...ParameterMutationsDefault,
  },
  actions: {
    ...ParameterActionsDefault,
  },
};

export default ParameterModule;
