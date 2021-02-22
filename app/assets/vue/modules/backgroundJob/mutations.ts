import { AxiosError } from "axios";

import { IListResponse } from "../../api";
import { IReadMutations, ReadMutations } from "../../store/mutations";

import { IBackgroundJobState } from "./state";
import { IBackgroundJob } from "./interfaces";

export interface IBackgroundJobMutations extends IReadMutations<IBackgroundJob, IBackgroundJobState> {
}

export const BackgroundJobMutationsDefault: IBackgroundJobMutations = {
  ...ReadMutations,
};
