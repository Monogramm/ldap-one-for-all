import { IEntityApiState, AbstractEntityState } from "../../interfaces/state";
import { IUser, User } from "./interfaces";
import { UserAPI } from "./api";

/**
 * User store state interface.
 */
export interface IUserState extends IEntityApiState<IUser, UserAPI> {}

/**
 * User store state class.
 */
export class UserState extends AbstractEntityState<IUser>
  implements IUserState {
  api = UserAPI.Instance;
}

/**
 * Factory to generate new default User store state class.
 */
export const UserStateDefault = (): UserState => {
  return new UserState();
};
