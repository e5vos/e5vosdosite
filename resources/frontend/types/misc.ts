import { useFormik } from "formik";

export type RequiredFields<T, K extends keyof T> = T & Required<Pick<T, K>>;

type CRUDParamType<T> = {
    value: T;
};

/**
 * These are <Form> interfaces that are used to create, read, update, and delete a resource. They are styled and functional, but require placement on a page.
 */
export type CRUDInterface<T> = {
    Creator: ({ className }: CRUDForm) => JSX.Element;
    Reader?: ({ value, className }: CRUDParamType<T> & CRUDForm) => JSX.Element;
    Updater: ({ value, className }: CRUDParamType<T> & CRUDForm) => JSX.Element;
    Deleter?: ({
        value,
        className,
    }: CRUDParamType<T> & CRUDForm) => JSX.Element;
};

export type CRUDForm = Omit<
    React.DetailedHTMLProps<
        React.InputHTMLAttributes<HTMLFormElement>,
        HTMLFormElement
    >,
    "onSubmit" | "value" | keyof Parameters<typeof useFormik>[0]
>;
