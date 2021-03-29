import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { ILdap } from "./interfaces";

import { ILdapState, LdapStateDefault } from "./state";
import { LdapGettersDefault } from "./getters";
import { LdapMutationsDefault } from "./mutations";
import { LdapActionsDefault } from "./actions";

export const MediaModule: Module<ILdapState, IRootState> = {
  namespaced: true,
  state: LdapStateDefault,
  getters: {
    ...LdapGettersDefault,
  },
  mutations: {
    ...LdapMutationsDefault,
  },
  actions: {
    ...LdapActionsDefault,
  },
};

export default MediaModule;
