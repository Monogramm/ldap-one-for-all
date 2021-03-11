import { IEntityApiState, AbstractEntityState } from "../../interfaces/state";
import { IMedia, Media } from "./interfaces";
import { MediaAPI } from "./api";

/**
 * Media store state interface.
 */
export interface IMediaState extends IEntityApiState<IMedia, MediaAPI> {}

/**
 * Media store state class.
 */
export class MediaState extends AbstractEntityState<IMedia>
  implements IMediaState {
  api = MediaAPI.Instance;

  initCurrent(): IMedia {
    return new Media();
  }
}

/**
 * Factory to generate new default Media store state class.
 */
export const MediaStateDefault = (): MediaState => {
  return new MediaState();
};
