import { IEntity, Entity } from "../../../interfaces/entity";

/**
 * Currency interface.
 */
export interface ICurrency extends IEntity {
  name: string;
  isoCode: string;
}

/**
 * Currency class.
 */
export class Currency extends Entity implements ICurrency {
  name: string = "";
  isoCode: string = "";
}

/**
 * Factory to generate new default Currency class.
 */
export const CurrencyDefault = (): Currency => {
  return new Currency();
};
