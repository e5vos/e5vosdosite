import { useFormik } from "formik";

export type RequiredFields<T, K extends keyof T> = T & Required<Pick<T, K>>;

/**
 * These are <Form> interfaces that are used to create, read, update, and delete a resource. They are styled and functional, but require placement on a page.
 */
export type CRUDInterface<T, K> = {
    Creator: ({ className }: CRUDFormImpl<T, K>) => JSX.Element;
    Reader?: ({ value, className }: CRUDFormImpl<T, K>) => JSX.Element;
    Updater: ({ value, className }: CRUDFormImpl<T, K>) => JSX.Element;
    Deleter?: ({ value, className }: CRUDFormImpl<T, K>) => JSX.Element;
};

export type CRUDForm<T> = Omit<
    React.DetailedHTMLProps<
        React.InputHTMLAttributes<HTMLFormElement>,
        HTMLFormElement
    >,
    "onSubmit" | "value" | keyof Parameters<typeof useFormik>[0]
> & {
    initialValues: T;
    onSubmit: (values: T) => void;
    submitLabel?: string;
    resetOnSubmit?: boolean;
    enableReinitialize?: boolean;
};

export type CRUDFormImpl<T, K> = Omit<
    CRUDForm<T>,
    "initialValues" | "onSubmit"
> & { value: K };
