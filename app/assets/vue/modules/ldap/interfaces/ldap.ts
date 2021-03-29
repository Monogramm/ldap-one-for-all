import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * Entry interface.
 */
export interface ILdap extends IEntity {
    key: string;
    value: string;
}

/**
* Entryclass.
*/
export class Ldap extends Entity implements ILdap {
  key: string = "";
  value: string = "";
}

/**
 * Factory to generate new default Media class.
 */
export const LdapDefault = (): Ldap => {
  return new Ldap();
};