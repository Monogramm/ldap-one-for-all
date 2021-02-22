import { AxiosError } from "axios";

import { IListResponse } from "../../api";
import { IReadMutations, ReadMutations } from "../../store/mutations";

import { ICurrencyState } from "./state";
import { ICurrency } from "./interfaces";

export interface ICurrencyMutations extends IReadMutations<ICurrency, ICurrencyState> {}

export const CurrencyMutationsDefault: ICurrencyMutations = {
  ...ReadMutations,
};
