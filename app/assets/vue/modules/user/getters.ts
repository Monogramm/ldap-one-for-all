import { IGetters, BaseGetters } from "../../store/getters";

import { IUser, User} from "./interfaces";
import { IUserState } from "./state";

// XXX IGetters<IUser, IUserState>
export interface IUserGetters extends IGetters {
}

export const UserGettersDefault: IUserGetters = {
  ...BaseGetters,
}
