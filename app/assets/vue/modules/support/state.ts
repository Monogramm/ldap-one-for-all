import { IEntityApiState, AbstractEntityState } from "../../interfaces/state";
import { ISupport } from "./interfaces";
import { SupportAPI } from "./api";

/**
 * Support store state interface.
 */
export interface ISupportState extends IEntityApiState<ISupport, SupportAPI> {}

/**
 * Support store state class.
 */
export class SupportState extends AbstractEntityState<ISupport>
  implements ISupportState {
  api = SupportAPI.Instance;
}

/**
 * Factory to generate new default Support store state class.
 */
export const SupportStateDefault = (): SupportState => {
  return new SupportState();
};
