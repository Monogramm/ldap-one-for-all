import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * Support interface.
 */
export interface ISupport extends IEntity {
  subject: string;
  message: string;
}

/**
 * Support class.
 */
export class Support extends Entity implements ISupport {
  subject: string = "";
  message: string = "";
}

/**
 * Factory to generate new default Support class.
 */
export const SupportDefault = (): Support => {
  return new Support();
};
