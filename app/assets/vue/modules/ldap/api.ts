import axios from "axios";
import { ReadWriteApi } from "../../api";
import { ILdap } from "./interfaces";

/**
 * Ldap API service.
 */
export class LdapAPI extends ReadWriteApi<ILdap> {
  private static _instance: LdapAPI;

  public static get Instance(): LdapAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("admin/ldap");
  }
}