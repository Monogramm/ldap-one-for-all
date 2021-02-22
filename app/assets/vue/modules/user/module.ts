import { AxiosError } from "axios";
import { Module, ActionContext } from "vuex";

import { IRootState } from "@/vue/interfaces/state";

import { IUser } from "./interfaces";

import { IUserState, UserStateDefault } from "./state";
import { UserGettersDefault } from "./getters";
import { UserMutationsDefault } from "./mutations";
import { UserActionsDefault } from "./actions";

export const UserModule: Module<IUserState, IRootState> = {
  namespaced: true,
  state: UserStateDefault,
  getters: {
    ...UserGettersDefault,
  },
  mutations: {
    ...UserMutationsDefault,
  },
  actions: {
    ...UserActionsDefault,
  },
};

export default UserModule;
