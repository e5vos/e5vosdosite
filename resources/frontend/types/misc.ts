import { useFormik } from 'formik'
import { JSX } from 'react'

export type RequiredFields<T, K extends keyof T> = T & Required<Pick<T, K>>
export type OptionalFields<T, K extends keyof T> = Omit<T, K> &
    Partial<Pick<T, K>>
export type RequiredAndOmitFields<
    T,
    R extends keyof T,
    O extends keyof T,
> = Omit<RequiredFields<T, R>, O>
/**
 * These are <Form> interfaces that are used to create, read, update, and delete a resource. They are styled and functional, but require placement on a page.
 */
export type CRUDInterface<T, K> = {
    Creator: ({ value, className }: CRUDFormImpl<T, Partial<K>>) => JSX.Element
    Reader?: ({
        value,
        className,
    }: CRUDFormImpl<T, K> & { value: T }) => JSX.Element
    Updater: ({ value, className }: CRUDFormImpl<T, K>) => JSX.Element
    Deleter?: ({ value, className }: CRUDFormImpl<T, K>) => JSX.Element
}

export type CRUDImplData<T> = {
    initialValues: T
    onSubmit: (values: T) => void
    submitLabel?: string
    resetOnSubmit?: boolean
    enableReinitialize?: boolean
}

export type CRUDForm<T> = Omit<
    React.DetailedHTMLProps<
        React.InputHTMLAttributes<HTMLFormElement>,
        HTMLFormElement
    >,
    'onSubmit' | 'value' | keyof Parameters<typeof useFormik>[0]
> &
    CRUDImplData<T>

export type CRUDImpl<T, K = T> = Omit<
    CRUDImplData<T>,
    'initialValues' | 'onSubmit'
> & { value: K }

export type CRUDFormImpl<T, K = T> = Omit<
    CRUDForm<T>,
    'initialValues' | 'onSubmit'
> & { value: K }
