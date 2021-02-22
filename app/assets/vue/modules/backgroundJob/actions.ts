import { ActionContext } from "vuex";

import { IEntityState } from "../../interfaces/state";
import { IReadActions, ReadActions } from "../../store/actions";

import { IBackgroundJobState } from "./state";
import { IBackgroundJob } from "./interfaces";

/**
 * Background Job API actions interface.
 */
export interface IBackgroundJobActions extends IReadActions<IBackgroundJob, IBackgroundJobState> {
}

export const BackgroundJobActionsDefault: IBackgroundJobActions = {
  ...ReadActions
};
