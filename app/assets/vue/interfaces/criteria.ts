
/**
 * Criteria interface.
 */
export interface ICriteria {
  /** Filters. */
  [property: string]: any;
}

/**
 * Criteria class.
 */
export class Criteria implements ICriteria {
  [property: string]: any;

  constructor(filters: any) {
    Object.keys(filters).forEach(field => {
      if (filters[field] !== '') {
        this[field] = filters[field];
      }
    });
  }
}
