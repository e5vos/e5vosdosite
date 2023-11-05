import { useFormik } from "formik";
import * as Yup from "yup";

import { CRUDForm, CRUDFormImpl } from "types/misc";
import { Permission } from "types/models";

import Locale from "lib/locale";

import EventSearchCombobox from "components/Event/EventSearch";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

export type PermissionFormValues = Omit<Permission, "id"> & {
    id?: number;
};

const locale = Locale({
    hu: {
        required: "Kötelező mező",
        threeChars: "Pontosan 3 karakter",
        event: "Esemény",
        code: "Kód",
        submit: "Mentés",
    },
    en: {
        required: "Required",
        threeChars: "Exactly 3 characters",
        event: "Event",
        code: "Code",
        submit: "Submit",
    },
});

const PermissionForm = ({
    initialValues,
    onSubmit,
    enableReinitialize,
    resetOnSubmit,
    submitLabel = locale.submit,
    ...rest
}: CRUDForm<PermissionFormValues>) => {
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            event_id: Yup.number(),
            code: Yup.string()
                .length(3, locale.threeChars)
                .required(locale.required),
        }),
        onSubmit: (values) => {
            const val = onSubmit(values);
            if (resetOnSubmit) formik.resetForm();
            return val;
        },
        enableReinitialize: enableReinitialize,
    });

    return (
        <Form onSubmit={formik.handleSubmit}>
            <Form.Group>
                <Form.Label>{locale.event}</Form.Label>
                <EventSearchCombobox
                    onChange={(e) => formik.setFieldValue("event_id", e.id)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.code}</Form.Label>
                <Form.Control
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.code)}
                    value={formik.values.code}
                />
            </Form.Group>
            <Form.Group>
                <Button type="submit">{submitLabel}</Button>
            </Form.Group>
        </Form>
    );
};

export default PermissionForm;
