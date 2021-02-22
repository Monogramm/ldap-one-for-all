import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * BackgroundJob interface.
 */
export interface IBackgroundJob extends IEntity {
  name: string;
  lastExecution: Date;
  status: string;
}

/**
 * BackgroundJob class.
 */
export class BackgroundJob extends Entity implements IBackgroundJob {
  name: string = null;
  lastExecution: Date = null;
  status: string = null;
}

/**
 * Factory to generate new default BackgroundJob class.
 */
export const BackgroundJobDefault = (): BackgroundJob => {
  return new BackgroundJob();
};
