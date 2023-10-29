import { useFormik } from "formik";
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import * as Yup from "yup";

import { Slot, SlotType } from "types/models";

import adminAPI from "lib/api/adminAPI";
import eventAPI from "lib/api/eventAPI";
import { isOperator } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        title: "Sáv szerkesztése",
        slot: {
            name: "Sáv neve",
            slot_type: "Sáv típusa",
            starts_at: "Sáv kezdete",
            ends_at: "Sáv vége",
        },
        submit: "Frissítés",
        required: "Kötelező mező",
    },
    en: {
        title: "Edit slot",
        slot: {
            name: "Slot name",
            slot_type: "Slot type",
            starts_at: "Slot start",
            ends_at: "Slot end",
        },
        submit: "Update",
        required: "Required",
    },
});
const EditSlotPage = () => {
    const { slotid } = useParams();
    const [updateSelectedSlot] = adminAPI.useUpdateSlotMutation();
    const { data: slots } = eventAPI.useGetSlotsQuery();
    const [initialValues, setInitialValue] = useState({
        id: Number(null),
        name: "",
        slot_type: "",
        starts_at: "",
        ends_at: "",
    });

    useEffect(() => {
        if (!slots) return;
        const slot = slots.find((slot) => Number(slot.id) === Number(slotid));
        if (!slot) return;
        setInitialValue({
            id: Number(slotid),
            name: slot.name,
            slot_type: slot.slot_type,
            starts_at: slot.starts_at,
            ends_at: slot.ends_at,
        });
    }, [slots]);

    const formik = useFormik({
        initialValues: initialValues,
        enableReinitialize: true,
        validationSchema: Yup.object({
            id: Yup.number().required(locale.required),
            name: Yup.string().required(locale.required),
            slot_type: Yup.string()
                .required(locale.required)
                .oneOf(Object.values(SlotType)),
            starts_at: Yup.string().required(locale.required),
            ends_at: Yup.string().required(locale.required),
        }),
        onSubmit: async (values) => {
            const updatedSlot = {
                id: values.id,
                name: values.name,
                slot_type: values.slot_type,
                starts_at: values.starts_at,
                ends_at: values.ends_at,
            };
            if (!values.id) return;
            console.log(updateSelectedSlot);
            await updateSelectedSlot(updatedSlot as Slot);
        },
    });

    if (initialValues.name === "") return <Loader />;
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

export default gated(EditSlotPage, isOperator);
