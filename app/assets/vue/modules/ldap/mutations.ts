import { AxiosError } from "axios";

import { IListResponse } from "../../api";
import { IReadWriteMutations, ReadWriteMutations } from "../../store/mutations";

import { ILdapState } from "./state";
import { ILdap } from "./interfaces";

export interface ILdapMutations extends IReadWriteMutations<ILdap, ILdapState> {
}

export const LdapMutationsDefault: ILdapMutations = {
  ...ReadWriteMutations,
};
