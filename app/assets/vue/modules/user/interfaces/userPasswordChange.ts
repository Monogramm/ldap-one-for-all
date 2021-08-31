
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

  constructor(
    public oldPassword: string = "",
    public newPassword: string = null,
    public confirmPassword: string = null,
  ) {}

  isValid(): boolean {
    return (
      this.oldPassword !== "" &&
      !!this.newPassword &&
      !!this.confirmPassword &&
      this.newPassword === this.confirmPassword
    );
  }
}

/**
 * Factory to generate new default User Password Change class.
 */
export const UserPasswordChangeDefault = (): IUserPasswordChange => {
  return new UserPasswordChange();
};
