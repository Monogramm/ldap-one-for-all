import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { IBackgroundJob } from "./interfaces";

import { IBackgroundJobState, BackgroundJobStateDefault } from "./state";
import { BackgroundJobGettersDefault } from "./getters";
import { BackgroundJobMutationsDefault } from "./mutations";
import { BackgroundJobActionsDefault } from "./actions";

export const BackgroundJobModule: Module<IBackgroundJobState, IRootState> = {
  namespaced: true,
  state: BackgroundJobStateDefault,
  getters: {
    ...BackgroundJobGettersDefault,
  },
  mutations: {
    ...BackgroundJobMutationsDefault,
  },
  actions: {
    ...BackgroundJobActionsDefault,
  },
};

export default BackgroundJobModule;
