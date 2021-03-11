import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { IMedia } from "./interfaces";

import { IMediaState, MediaStateDefault } from "./state";
import { MediaGettersDefault } from "./getters";
import { MediaMutationsDefault } from "./mutations";
import { MediaActionsDefault } from "./actions";

export const MediaModule: Module<IMediaState, IRootState> = {
  namespaced: true,
  state: MediaStateDefault,
  getters: {
    ...MediaGettersDefault,
  },
  mutations: {
    ...MediaMutationsDefault,
  },
  actions: {
    ...MediaActionsDefault,
  },
};

export default MediaModule;
