import { ReadApi } from "../../api";
import { ICurrency } from "./interfaces";

/**
 * Currency API service.
 */
export class CurrencyAPI extends ReadApi<ICurrency> {
  private static _instance: CurrencyAPI;

  public static get Instance(): CurrencyAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("currency");
  }
};

export default CurrencyAPI;
