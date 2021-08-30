import axios from "axios";
import { ReadWriteApi } from "../../api";
import { ILdapEntry } from "./interfaces";

/**
 * LdapEntry API service.
 */
export class LdapEntryAPI extends ReadWriteApi<ILdapEntry> {
  private static _instance: LdapEntryAPI;

  public static get Instance(): LdapEntryAPI {
    return this._instance || (this._instance = new this());
  }

  private constructor() {
    super("ldap", "admin/ldap");
  }

  updateCurrentUser(entry: ILdapEntry) {
    return axios.put<ILdapEntry>(`${this.base}/${this.roPrefix}`, entry);
  }

  /**
   * Patch an existing LDAP entry.
   * 
   * @param entityId LDAP Entry identification.
   */
  patch(entityId: string, entry: ILdapEntry) {
    return axios.patch<ILdapEntry>(`${this.base}/${this.rwPrefix}/${entityId}`, entry);
  }
};
