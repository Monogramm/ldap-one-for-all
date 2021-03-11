import { AxiosError } from "axios";

import { IListResponse } from "../../api";
import { IReadWriteMutations, ReadWriteMutations } from "../../store/mutations";

import { IMediaState } from "./state";
import { IMedia } from "./interfaces";

export interface IMediaMutations extends IReadWriteMutations<IMedia, IMediaState> {
}

export const MediaMutationsDefault: IMediaMutations = {
  ...ReadWriteMutations,
};
