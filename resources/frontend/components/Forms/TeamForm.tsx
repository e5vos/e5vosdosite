import { FormikValues, useFormik } from "formik";
import * as Yup from "yup";

import { CRUDForm } from "types/misc";

import Locale from "lib/locale";

import { TeamFormValues } from "components/Team/CRUD";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const locale = Locale({
    hu: {
        required: "Kötelező mező",
        gte2: "Legalább 2 karakter",
        lte11: "Legfeljebb 10 karakter",
        mustBeLetters: "Csak betűk lehetnek",
        submit: "Létrehozás",
        labels: {
            name: "Név",
            code: "Kód",
            description: "Leírás",
        },
    },
    en: {
        required: "Required",
        gte2: "At least 2 characters",
        lte11: "At most 10 characters",
        mustBeLetters: "Must be letters only",
        submit: "Create",
        labels: {
            name: "Name",
            code: "Code",
            description: "Description",
        },
    },
});

const TeamForm = ({
    initialValues,
    onSubmit,
    submitLabel = locale.submit,
    enableReinitialize = false,
    resetOnSubmit = false,
    ...rest
}: {
    initialValues: TeamFormValues;
    onSubmit: (values: TeamFormValues) => void;
    submitLabel?: string;
    enableReinitialize?: boolean;
    resetOnSubmit?: boolean;
} & CRUDForm) => {
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            name: Yup.string().required(locale.required),
            code: Yup.string()
                .required(locale.required)
                .min(2, locale.gte2)
                .max(10, locale.lte11)
                .matches(/^[a-zA-Z]{1,10}$/, locale.mustBeLetters)
                .matches(/^[a-zA-Z]+$/, locale.mustBeLetters),
            description: Yup.string(),
        }),
        onSubmit: (values) => {
            const val = onSubmit(values);
            if (resetOnSubmit) formik.resetForm();
            return val;
        },
        enableReinitialize: enableReinitialize,
    });
    return (
        <Form onSubmit={formik.handleSubmit} {...rest}>
            <Form.Group>
                <Form.Label>{locale.labels.name}</Form.Label>
                <Form.Control
                    name="name"
                    value={formik.values.name}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.name)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.labels.code}</Form.Label>
                <Form.Control
                    name="code"
                    value={formik.values.code}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.code)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.labels.description}</Form.Label>
                <Form.Control
                    name="description"
                    value={formik.values.description}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.description)}
                />
            </Form.Group>
            <Form.Group>
                <Button type="submit">{submitLabel}</Button>
            </Form.Group>
        </Form>
    );
};

export default TeamForm;
