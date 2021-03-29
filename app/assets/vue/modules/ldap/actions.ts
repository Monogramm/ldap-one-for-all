import { ActionContext } from "vuex";
import { AxiosResponse } from "axios";

import { IRootState } from "../../interfaces/state";
import { IReadWriteActions, ReadWriteActions } from "../../store/actions";

import { ILdapState } from "./state";
import { ILdap } from "./interfaces";

export interface ILdapPayload {
  entry: ILdap;
}

/**
 * Ldap API actions interface.
 */
export interface ILdapActions extends IReadWriteActions<ILdap, ILdapState> {

}

export const LdapActionsDefault: ILdapActions = {
  ...ReadWriteActions,
};
