import { IGetters, BaseGetters } from "../../store/getters";
import { IMedia } from "./interfaces";
import { IMediaState } from "./state";

// XXX IGetters<IMedia, IMediaState>
export interface IMediaGetters extends IGetters {}

export const MediaGettersDefault: IMediaGetters = {
  ...BaseGetters
}
