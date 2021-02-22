
/**
 * User Password Change interface.
 */
export interface IUserPasswordChange {
  oldPassword: string;
  newPassword: string;
  confirmPassword: string;

  isValid(): boolean;
}

/**
 * User Password Change class.
 */
export class UserPasswordChange implements IUserPasswordChange {
  oldPassword: string = "";
  newPassword: string = null;
  confirmPassword: string = null;

  isValid(): boolean {
    return this.newPassword === this.confirmPassword;
  }
}

/**
 * Factory to generate new default User Password Change class.
 */
export const UserPasswordChangeDefault = (): IUserPasswordChange => {
  return new UserPasswordChange();
};
