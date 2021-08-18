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
  enabled: boolean;
  metadata: Array<any>;
}

/**
 * User class.
 */
export class User extends Entity implements IUser {
  static DEFAULT_LANGUAGE = "en";

  constructor(
    public email: string = "",
    public username: string = "",
    public language: string = User.DEFAULT_LANGUAGE,
    public roles: Array<string> = [],
    public isVerified: boolean = false,
    public enabled: boolean = true,
    public metadata: Array<any> = [],
    id: string = null,
    createdAt: Date = new Date(),
    updatedAt: Date = new Date(),
  ) {
    super(id, createdAt, updatedAt);
  }
}

/**
 * Factory to generate new default User class.
 */
export const UserDefault = (): User => {
  return new User();
};
