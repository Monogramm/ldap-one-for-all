import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * Entry interface.
 */
export interface IEntry extends IEntity {
  key: string;
  value: string;
}

/**
 * Entryclass.
 */
export class Entry extends Entity implements IEntry {
  key: string = "";
  value: string = "";
}

/**
 * Factory to generate new default Media class.
 */
export const EntryDefault = (): Entry => {
  return new Entry();
};
