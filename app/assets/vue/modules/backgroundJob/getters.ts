import { IGetters, BaseGetters } from "../../store/getters";
import { IBackgroundJob } from "./interfaces";
import { IBackgroundJobState } from "./state";

// XXX IGetters<IBackgroundJob, IBackgroundJobState>
export interface IBackgroundJobGetters extends IGetters {}

export const BackgroundJobGettersDefault: IBackgroundJobGetters = {
  ...BaseGetters,
}
