import { IRootState } from "@/vue/interfaces/state";

/**
 * Login interface.
 */
export interface ILogin {
  accessToken: string;
  refreshToken: string;
  rootState: IRootState;
}

/**
 * Login class.
 */
export class Login implements ILogin {
  accessToken: string = null;
  refreshToken: string = null;
  rootState: IRootState = null;
}

/**
 * Factory to generate new default Login class.
 */
export const LoginDefault = (): ILogin => {
  return new Login();
};
