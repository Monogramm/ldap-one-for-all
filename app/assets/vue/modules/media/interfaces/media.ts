import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * Media interface.
 */
export interface IMedia extends IEntity {
  name: string;
  filename: string;
  description: string;
  type: string;
}

/**
 * Media class.
 */
export class Media extends Entity implements IMedia {
  name: string = "";
  filename: string = "";
  description: string = "";
  type: string = null;
}

/**
 * Factory to generate new default Media class.
 */
export const MediaDefault = (): Media => {
  return new Media();
};
