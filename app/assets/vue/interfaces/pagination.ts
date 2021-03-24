import { ISort } from "./sort";
import { ICriteria } from "./criteria";

/**
 * Pagination interface.
 */
export interface IPagination {
  /** Page number. */
  page: number|null;
  /** Page size. */
  size: number|null;
  /** Filters criteria. */
  criteria: ICriteria;
  /** Sorting orders. */
  orderBy: ISort;
}

/**
 * Pagination class.
 */
export class Pagination implements IPagination {
  constructor(public size: number = 20,
    public page: number = 1,
    public criteria: ICriteria = null,
    public orderBy: ISort = null,
  ) {
  }
}
