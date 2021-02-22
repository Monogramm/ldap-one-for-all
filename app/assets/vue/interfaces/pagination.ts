/**
 * Pagination interface.
 */
export interface IPagination {
  /** Page number. */
  page: number;
  /** Page size. */
  size: number;
}

/**
 * Pagination class.
 */
export class Pagination implements IPagination {
  constructor(public size: number = 20,
    public page: number = 1) {
  }
}
