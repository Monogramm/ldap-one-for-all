import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { ISupport } from "./interfaces";

import { ISupportState, SupportStateDefault } from "./state";
import { SupportGettersDefault } from "./getters";
import { SupportMutationsDefault } from "./mutations";
import { SupportActionsDefault } from "./actions";

export const SupportModule: Module<ISupportState, IRootState> = {
  namespaced: true,
  state: SupportStateDefault,
  getters: {
    ...SupportGettersDefault,
  },
  mutations: {
    ...SupportMutationsDefault,
  },
  actions: {
    ...SupportActionsDefault,
  },
};

export default SupportModule;
