import { useFormik } from "formik";
import useUser from "hooks/useUser";
import * as Yup from "yup";

import { CRUDForm } from "types/misc";

import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import { EventFormValues } from "components/Event/CRUD";
import LocationSearchCombobox from "components/Location/LocationSelect";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const locale = Locale({
    hu: {
        submit: "Mentés",
        required: "Kötelező mező",
        fields: {
            name: "Név",
            description: "Leírás",
            starts_at: "Kezdés",
            ends_at: "Befejezés",
            organiser: "Szervező",
            location: "Helyszín",
            capacity: "Kapacitás",
            is_competition: "Verseny?",
        },
    },
    en: {
        submit: "Submit",
        required: "Required",
        fields: {
            name: "Name",
            description: "Description",
            starts_at: "Starts at",
            ends_at: "Ends at",
            organiser: "Organiser",
            location: "Location",
            capacity: "Capacity",
            is_competition: "Is competition?",
        },
    },
});

const EventForm = ({
    initialValues,
    onSubmit,
    submitLabel = locale.submit,
    enableReinitialize = false,
    resetOnSubmit = false,
    ...rest
}: CRUDForm<EventFormValues>) => {
    const { user } = useUser(false);
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            name: Yup.string().required(locale.required),
            description: Yup.string().required(locale.required),
            starts_at: Yup.string().required(locale.required),
            ends_at: Yup.string().required(locale.required),
            organiser: Yup.string().required(locale.required),
            location: Yup.string().required(locale.required),
            capacity: Yup.string().required(locale.required),
            is_competition: Yup.boolean().required(locale.required),
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
            <Form onSubmit={formik.handleSubmit}>
                <Form.Group>
                    <Form.Label>{locale.fields.name}</Form.Label>
                    <Form.Control
                        name="name"
                        value={formik.values.name}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.name)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.fields.description}</Form.Label>
                    <Form.Control
                        name="description"
                        value={formik.values.description}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.description)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.fields.starts_at}</Form.Label>
                    <Form.Control
                        name="starts_at"
                        value={formik.values.starts_at}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.starts_at)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.fields.ends_at}</Form.Label>
                    <Form.Control
                        name="ends_at"
                        value={formik.values.ends_at}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.ends_at)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.fields.organiser}</Form.Label>
                    <Form.Control
                        name="organiser"
                        value={formik.values.organiser}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.organiser)}
                    />
                </Form.Group>
                {isAdmin(user) && (
                    <Form.Group>
                        <Form.Label>{locale.fields.location}</Form.Label>
                        <LocationSearchCombobox
                            onChange={(e) =>
                                formik.setFieldValue("location", e.id)
                            }
                        />
                    </Form.Group>
                )}
                {isAdmin(user) && (
                    <Form.Group>
                        <Form.Label>{locale.fields.capacity}</Form.Label>
                        <Form.Control
                            type="number"
                            name="capacity"
                            value={formik.values.capacity}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            invalid={Boolean(formik.errors.capacity)}
                        />
                    </Form.Group>
                )}
                {isAdmin(user) && (
                    <Form.Group>
                        <Form.Label>{locale.fields.is_competition}</Form.Label>
                        <Form.Check
                            name="is_competition"
                            checked={formik.values.is_competition}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            invalid={Boolean(formik.errors.is_competition)}
                        />
                    </Form.Group>
                )}

                <Form.Group>
                    <Button type="submit">{locale.submit}</Button>
                </Form.Group>
            </Form>
        </Form>
    );
};

export default EventForm;
