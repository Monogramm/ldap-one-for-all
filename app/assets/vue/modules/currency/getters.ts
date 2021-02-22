import {IGetters, BaseGetters} from "../../store/getters";
import { ICurrency } from "./interfaces";
import { ICurrencyState } from "./state";

// XXX IGetters<ICurrency, ICurrencyState>
export interface ICurrencyGetters extends IGetters {}

export const CurrencyGettersDefault: ICurrencyGetters = {
  ...BaseGetters,
}
