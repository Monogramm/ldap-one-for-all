import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { ILogin } from "./interfaces";

import { IAuthState, AuthStateDefault } from "./state";
import { AuthGettersDefault } from "./getters";
import { AuthMutationsDefault } from "./mutations";
import { AuthActionsDefault } from "./actions";

export const AuthModule: Module<IAuthState, IRootState> = {
  namespaced: true,
  state: AuthStateDefault,
  getters: {
    ...AuthGettersDefault,
  },
  mutations: {
    ...AuthMutationsDefault,
  },
  actions: {
    ...AuthActionsDefault,
  },
};

export default AuthModule;
