import { IGetters, BaseGetters } from "../../store/getters";
import { IParameter } from "./interfaces";
import { IParameterState } from "./state";

// XXX IGetters<IParameter, IParameterState>
export interface IParameterGetters extends IGetters {}

export const ParameterGettersDefault: IParameterGetters = {
  ...BaseGetters
}
