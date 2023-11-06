import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

import { Slot } from "types/models";

import eventAPI from "lib/api/eventAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import SlotCRUD from "components/Slot/CRUD";
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
    const { data: slots } = eventAPI.useGetSlotsQuery();
    const [initialValues, setInitialValue] = useState<Slot>({
        id: -1,
        name: "",
        slot_type: "Előadássáv",
        starts_at: "",
        ends_at: "",
    });

    useEffect(() => {
        const slotIdAsNum = Number(slotid);
        const slot = slots?.find((slot) => slot.id === slotIdAsNum);
        if (!slot) return;
        setInitialValue(slot);
    }, [slotid, slots]);

    if (initialValues.name === "") return <Loader />;
    return (
        <div>
            <div className="mx-auto max-w-4xl">
                <h1 className="text-center text-4xl font-bold">
                    {locale.title}
                </h1>
            </div>
            <div className="min-w-12 mx-28 mt-2 flex flex-col justify-center align-middle">
                <SlotCRUD.Updater value={initialValues} />
            </div>
        </div>
    );
};

export default gated(EditSlotPage, isAdmin);
