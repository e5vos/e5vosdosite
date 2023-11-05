import { useFormik } from "formik";
import * as Yup from "yup";

import { CRUDForm } from "types/misc";
import { SlotType } from "types/models";

import Locale from "lib/locale";

import { SlotFormValues } from "components/Slot/CRUD";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const locale = Locale({
    hu: {
        title: "Sáv létrehozása",
        slot: {
            name: "Sáv neve",
            slot_type: "Sáv típusa",
            slot_types: {
                presentation: "Előadás",
                event: "Program",
            },
            starts_at: "Sáv kezdete",
            ends_at: "Sáv vége",
        },
        submit: "Létrehozás",
        required: "Kötelező mező",
    },
    en: {
        title: "Create slot",
        slot: {
            name: "Slot name",
            slot_type: "Slot type",
            slot_types: {
                presentation: "Presentation",
                event: "Event",
            },
            starts_at: "Slot start",
            ends_at: "Slot end",
        },
        submit: "Create",
        required: "Required",
    },
});

const SlotForm = ({
    initialValues,
    onSubmit,
    submitLabel = locale.submit,
    enableReinitialize = false,
    resetOnSubmit = false,
    ...rest
}: CRUDForm<SlotFormValues>) => {
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            id: Yup.number().integer().notRequired(),
            name: Yup.string().required(locale.required),
            slot_type: Yup.string().required(locale.required),
            starts_at: Yup.string().required(locale.required),
            ends_at: Yup.string().required(locale.required),
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
                <Form.Label>{locale.slot.name}</Form.Label>
                <Form.Control
                    name="name"
                    value={formik.values.name}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.name)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.slot.slot_type}</Form.Label>
                <Form.Select
                    defaultValue={initialValues.slot_type}
                    name="slot_type"
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                >
                    {Object.entries(SlotType).map(([key, value]) => (
                        <option key={key} value={value}>
                            {value}
                        </option>
                    ))}
                </Form.Select>
            </Form.Group>
            <div className="grid-cols-2 gap-10 sm:grid">
                <Form.Group>
                    <Form.Label>{locale.slot.starts_at}</Form.Label>
                    <Form.Control
                        type="datetime-local"
                        name="starts_at"
                        value={formik.values.starts_at}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.starts_at)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.slot.ends_at}</Form.Label>
                    <Form.Control
                        type="datetime-local"
                        name="ends_at"
                        value={formik.values.ends_at}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.ends_at)}
                    />
                </Form.Group>
            </div>

            <Form.Group>
                <Button type="submit">{submitLabel}</Button>
            </Form.Group>
        </Form>
    );
};
export default SlotForm;
