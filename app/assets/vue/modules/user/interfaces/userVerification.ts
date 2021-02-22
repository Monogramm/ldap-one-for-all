
/**
 * User Verification interface.
 */
export interface IUserVerification {
  code: string;
}

/**
 * User Verification class.
 */
export class UserVerification implements IUserVerification {
  code: string = null;
}

/**
 * Factory to generate new default User Verification class.
 */
export const UserVerificationDefault = (): IUserVerification => {
  return new UserVerification();
};
