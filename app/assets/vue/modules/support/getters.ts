import { IGetters, BaseGetters } from "../../store/getters";
import { ISupport } from "./interfaces";
import { ISupportState } from "./state";

// XXX IGetters<ISupport, ISupportState>
export interface ISupportGetters extends IGetters {}

export const SupportGettersDefault: ISupportGetters = {
  ...BaseGetters,
}
