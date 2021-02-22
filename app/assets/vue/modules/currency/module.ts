import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { ICurrency } from "./interfaces";

import { ICurrencyState, CurrencyStateDefault } from "./state";
import { CurrencyGettersDefault } from "./getters";
import { CurrencyMutationsDefault } from "./mutations";
import { CurrencyActionsDefault } from "./actions";

export const CurrencyModule: Module<ICurrencyState, IRootState> = {
  namespaced: true,
  state: CurrencyStateDefault,
  getters: {
    ...CurrencyGettersDefault,
  },
  mutations: {
    ...CurrencyMutationsDefault,
  },
  actions: {
    ...CurrencyActionsDefault,
  },
};

export default CurrencyModule;
