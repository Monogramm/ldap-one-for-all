
/**
 * Sort direction.
 */
enum SortDirection {
  ASC = "asc",
  DESC = "desc",
}

/**
 * Sorting interface.
 */
export interface ISort {
  /** Sort orders. */
  [property: string]: SortDirection;
}

/**
 * Sorting class.
 */
export class Sort implements ISort {
  [property: string]: SortDirection;

  constructor(field: string,
    direction: string = SortDirection.ASC
  ) {
    if (direction === SortDirection.ASC) {
      this[field] = SortDirection.ASC;
    } else if (direction === SortDirection.DESC) {
      this[field] = SortDirection.DESC;
    }
  }
}
