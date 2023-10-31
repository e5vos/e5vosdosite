import { useEffect, useState } from "react";
import { useCallback } from "react";
import { useNavigate, useParams } from "react-router-dom";

import { Slot } from "types/models";

import adminAPI from "lib/api/adminAPI";
import eventAPI from "lib/api/eventAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import SlotForm, { SlotFormValues } from "components/Forms/SlotForm";
import { gated } from "components/Gate";
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
    const navigate = useNavigate();
    const [initialValues, setInitialValue] = useState<SlotFormValues>({
        id: -1,
        name: "",
        slot_type: "Előadássáv",
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
    }, [slotid, slots]);

    const onSubmit = useCallback(
        async (values: any) => {
            await updateSelectedSlot(values as Slot);
            navigate("/admin/sav");
        },
        [updateSelectedSlot, navigate],
    );

    if (initialValues.name === "") return <Loader />;
    return (
        <SlotForm
            initialValues={initialValues}
            onSubmit={onSubmit}
            submitLabel={locale.submit}
            enableReinitialize={true}
        />
    );
};

export default gated(EditSlotPage, isAdmin);
