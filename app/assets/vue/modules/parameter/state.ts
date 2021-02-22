import { IEntityApiState, AbstractEntityState } from "../../interfaces/state";
import { IParameter, Parameter } from "./interfaces";
import { ParameterAPI } from "./api";

/**
 * Parameter store state interface.
 */
export interface IParameterState extends IEntityApiState<IParameter, ParameterAPI> {}

/**
 * Parameter store state class.
 */
export class ParameterState extends AbstractEntityState<IParameter>
  implements IParameterState {
  api = ParameterAPI.Instance;

  initCurrent(): IParameter {
    return new Parameter();
  }
}

/**
 * Factory to generate new default Parameter store state class.
 */
export const ParameterStateDefault = (): ParameterState => {
  return new ParameterState();
};
