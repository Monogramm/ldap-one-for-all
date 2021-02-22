import { IEntityReadApiState, AbstractEntityState } from "../../interfaces/state";
import { IBackgroundJob } from "./interfaces";
import { BackgroundJobAPI } from "./api";

/**
 * BackgroundJob store state interface.
 */
export interface IBackgroundJobState extends IEntityReadApiState<IBackgroundJob, BackgroundJobAPI> {
}

/**
 * BackgroundJob store state class.
 */
export class BackgroundJobState extends AbstractEntityState<IBackgroundJob>
  implements IBackgroundJobState {
  api = BackgroundJobAPI.Instance;
}

/**
 * Factory to generate new default BackgroundJob store state class.
 */
export const BackgroundJobStateDefault = (): BackgroundJobState => {
  return new BackgroundJobState();
};
