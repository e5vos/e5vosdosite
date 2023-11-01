import { FormikValues, useFormik } from "formik";
import * as Yup from "yup";

import Locale from "lib/locale";

import { TeamFormValues } from "components/Team/CRUD";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const locale = Locale({
    hu: {
        required: "Kötelező mező",
        mustBe12: "12 karakter hosszú kell legyen",
        mustBeLetters: "Csak betűk lehetnek",
        submit: "Létrehozás",
    },
    en: {
        required: "Required",
        mustBe12: "Must be 12 characters",
        mustBeLetters: "Must be letters only",
        submit: "Create",
    },
});

const TeamForm = ({
    initialValues,
    onSubmit,
    submitLabel = locale.form.submit,
    ...rest
}: {
    initialValues: TeamFormValues;
    onSubmit: (values: TeamFormValues) => void;
    submitLabel?: string;
} & Omit<Parameters<typeof useFormik>[0], "onSubmit">) => {
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            name: Yup.string().required(locale.required),
            code: Yup.string()
                .required(locale.required)
                .length(12, locale.mustBe12)
                .matches(/^[a-zA-Z]+$/, locale.mustBeLetters),
            description: Yup.string(),
        }),
        onSubmit: onSubmit as (values: FormikValues) => void,
        ...rest,
    });
    return (
        <Form onSubmit={formik.handleSubmit}>
            <Form.Group>
                <Form.Label>Name</Form.Label>
                <Form.Control
                    name="name"
                    value={formik.values.name}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.name)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>Code</Form.Label>
                <Form.Control
                    name="code"
                    value={formik.values.code}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.code)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>Description</Form.Label>
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
