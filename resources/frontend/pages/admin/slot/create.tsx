import { useFormik } from "formik";
import * as Yup from "yup";

import adminAPI from "lib/api/adminAPI";
import { isOperator } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const locale = Locale({
    hu: {
        title: "Sáv létrehozása",
        slot: {
            name: "Sáv neve",
            slot_type: "Sáv típusa",
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
            starts_at: "Slot start",
            ends_at: "Slot end",
        },
        submit: "Create",
        required: "Required",
    },
});

const initialValues: {
    name: string;
    slot_type: string;
    starts_at: string;
    ends_at: string;
} = {
    name: "",
    slot_type: "",
    starts_at: "",
    ends_at: "",
};

const SlotsCreatePage = () => {
    const [createSlot] = adminAPI.useCreateSlotMutation();
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            name: Yup.string().required(locale.required),
            slot_type: Yup.string().required(locale.required),
            starts_at: Yup.string().required(locale.required),
            ends_at: Yup.string().required(locale.required),
        }),
        onSubmit: async (values) => {
            let newSlot = { ...values };
            console.log(newSlot);
            await createSlot(newSlot);
        },
    });
    return (
        <>
            <div className="mx-auto max-w-4xl">
                <h1 className="text-center text-4xl font-bold">
                    {locale.title}
                </h1>
            </div>
            <div className="min-w-12 mx-28 mt-2 flex flex-col justify-center align-middle">
                <Form onSubmit={formik.handleSubmit}>
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
                        <Form.Control
                            name="slot_type"
                            value={formik.values.slot_type}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            invalid={Boolean(formik.errors.slot_type)}
                        />
                    </Form.Group>
                    <Form.Group>
                        <Form.Label>{locale.slot.starts_at}</Form.Label>
                        <Form.Control
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
                            name="ends_at"
                            value={formik.values.ends_at}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            invalid={Boolean(formik.errors.ends_at)}
                        />
                    </Form.Group>

                    <Form.Group>
                        <Button type="submit">{locale.submit}</Button>
                    </Form.Group>
                </Form>
            </div>
        </>
    );
};

export default gated(SlotsCreatePage, isOperator);
