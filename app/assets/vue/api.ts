import axios from "axios";

/**
 * API service interface.
 */
export interface Api {}

/**
 * Abstract API service.
 */
export abstract class AbstractApi implements Api {
  private _apiPrefix: string;
  private _base: string;

  constructor(apiPrefix: string, base: string = '/api') {
    this._apiPrefix = apiPrefix;
    this._base = base;
  }

  /** Type prefix for API operations. */
  public get roPrefix() : string {
    return this._apiPrefix;
  }

  /** API base. Defaults to '/api'. */
  public get base() : string {
    return this._base;
  }

};

/**
 * API response for listing paginated entities.
 */
export interface IListResponse<T> {
  total: number;
  items: Array<T>;
}

/**
 * Abstract Read Only API service.
 * 
 * @param T Entity type.
 */
export abstract class ReadApi<T> extends AbstractApi {
  constructor(roPrefix: string, base: string = '/api') {
    super(roPrefix, base);
  }

  /**
   * Get all items paginated.
   *
   * @param page page number.
   * @param size page size.
   */
  getAll(page: number, size: number) {
    // TODO Make getAll work without pagination to retrieve all (with size = -1)
    return axios.get<IListResponse<T>>(`${this.base}/${this.roPrefix}`, {
      params: { page: page, size: size },
    });
  }

  /**
   * Get a single entity.
   *
   * @param entityId Entity ID.
   */
  get(entityId: string) {
    return axios.get<T>(`${this.base}/${this.roPrefix}/${entityId}`);
  }

};

/**
 * Abstract CRUD API service.
 * 
 * @param T Entity type.
 */
export abstract class ReadWriteApi<T> extends ReadApi<T> {
  private _rwPrefix: string;

  /**
   * Constructor for bstract CRUD API service.
   *
   * @param roApiPrefix Read Only API prefix.
   * @param rwApiPrefix Read Write API prefix. Defaults to same as Read Only API prefix.
   * @param base API base URL.
   */
  constructor(roApiPrefix: string, rwApiPrefix: string = roApiPrefix, base: string = '/api') {
    super(roApiPrefix, base);
    this._rwPrefix = rwApiPrefix;
  }

  /** Type prefix for Read Write API operations. */
  public get rwPrefix() : string {
    return this._rwPrefix;
  }

  /**
   * Create a new entity.
   *
   * @param entity Entity data.
   */
  create(entity: T) {
    return axios.post<T>(`${this.base}/${this.rwPrefix}`, entity);
  }

  /**
   * Update an existing entity.
   *
   * @param entityId Entitiy identification.
   * @param entity Entity data.
   */
  update(entityId: string, entity: T) {
    return axios.put<T>(`${this.base}/${this.rwPrefix}/${entityId}`, entity);
  }

  /**
   * Delete an existing entity.
   * 
   * @param entityId Entitiy identification.
   */
  delete(entityId: string) {
    return axios.delete<void>(`${this.base}/${this.rwPrefix}/${entityId}`);
  }

};

export default ReadWriteApi;
