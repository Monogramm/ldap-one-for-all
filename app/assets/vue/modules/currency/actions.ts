import { IReadActions, ReadActions } from "../../store/actions";

import { ICurrencyState } from "./state";
import { ICurrency } from "./interfaces";

/**
 * Currency API actions interface.
 */
export interface ICurrencyActions extends IReadActions<ICurrency, ICurrencyState> {
}

export const CurrencyActionsDefault: ICurrencyActions = {
  ...ReadActions,
};
