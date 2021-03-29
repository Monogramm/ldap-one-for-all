import { IEntityApiState, AbstractEntityState } from "../../interfaces/state";
import { ILdap, Ldap } from "./interfaces";
import { LdapAPI } from "./api";

/**
 * Ldap store state interface.
 */
export interface ILdapState extends IEntityApiState<ILdap, LdapAPI> {}

/**
 * Ldap store state class.
 */
export class LdapState extends AbstractEntityState<ILdap>
  implements ILdapState {
  api = LdapAPI.Instance;

  initCurrent(): ILdap {
    return new Ldap();
  }
}

/**
 * Factory to generate new default Ldap store state class.
 */
export const LdapStateDefault = (): LdapState => {
  return new LdapState();
};
