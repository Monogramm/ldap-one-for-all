import { IEntityReadApiState, AbstractEntityState } from "../../interfaces/state";
import { ICurrency } from "./interfaces";
import { CurrencyAPI } from "./api";

/**
 * Currency store state interface.
 */
export interface ICurrencyState extends IEntityReadApiState<ICurrency, CurrencyAPI> {}

/**
 * Currency store state class.
 */
export class CurrencyState extends AbstractEntityState<ICurrency>
  implements ICurrencyState {
  api = CurrencyAPI.Instance;
}

/**
 * Factory to generate new default Currency store state class.
 */
export const CurrencyStateDefault = (): CurrencyState => {
  return new CurrencyState();
};
