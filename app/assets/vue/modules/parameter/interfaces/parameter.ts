import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * Parameter interface.
 */
export interface IParameter extends IEntity {
  name: string;
  value: string;
  description: string;
  type: string;
}

/**
 * Parameter class.
 */
export class Parameter extends Entity implements IParameter {
  name: string = "";
  value: string = "";
  description: string = "";
  type: string = null;
}

/**
 * Factory to generate new default Parameter class.
 */
export const ParameterDefault = (): Parameter => {
  return new Parameter();
};
