import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * User interface.
 */
export interface IUser extends IEntity {
  email: string;
  username: string;
  language: string;
  roles: Array<string>;
  isVerified: boolean;
}

/**
 * User class.
 */
export class User extends Entity implements IUser {
  static DEFAULT_LANGUAGE = "en";

  email: string = "";
  username: string = "";
  language: string = User.DEFAULT_LANGUAGE;
  roles: Array<string> = [];
  isVerified: boolean = false;
}

/**
 * Factory to generate new default User class.
 */
export const UserDefault = (): User => {
  return new User();
};
