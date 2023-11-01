export type RequiredFields<T, K extends keyof T> = T & Required<Pick<T, K>>;

/**
 * These are <Form> interfaces that are used to create, read, update, and delete a resource. They are styled and functional, but require placement on a page.
 */
export type CRUDInterface<T> = {
    Creator: () => JSX.Element;
    Reader?: ({ value }: { value: T }) => JSX.Element;
    Updater: ({ value }: { value: T }) => JSX.Element;
    Deleter?: ({ value }: { value: T }) => JSX.Element;
};
