import { IGetters, BaseGetters } from "../../store/getters";

// XXX IGetters<ILdap, ILdapState>
export interface ILdapGetters extends IGetters {}

export const LdapGettersDefault: ILdapGetters = {
  ...BaseGetters
}