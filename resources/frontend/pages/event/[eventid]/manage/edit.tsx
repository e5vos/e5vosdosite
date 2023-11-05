import { useFormik } from "formik";
import { useParams } from "react-router-dom";
import * as Yup from "yup";

import { Location } from "types/models";

import eventAPI from "lib/api/eventAPI";
import locationAPI from "lib/api/locationAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import EventCRUD from "components/Event/CRUD";
import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        title: "Esemény szerkesztése",
        event: {
            name: "Esemény neve",
            description: "Esemény leírása",
            starts_at: "Esemény kezdete",
            ends_at: "Esemény vége",
            organiser: "Szervező",
            location: "Helyszín",
            capacity: "Kapacitás",
            is_competition: "Verseny",
        },
        required: "Kötelező mező",
        submit: "Szerkesztés",
    },
    en: {
        title: "Edit event",
        event: {
            name: "Event name",
            description: "Event description",
            starts_at: "Event start",
            ends_at: "Event end",
            organiser: "Organiser",
            location: "Location",
            capacity: "Capacity",
            is_competition: "Competition",
        },
        required: "Required field",
        submit: "Edit",
    },
});

const initialValues: {
    name: string;
    description: string;
    starts_at: string;
    ends_at: string;
    organiser: string;
    location_id: number | null;
    is_competition: boolean;
    capacity: string | number;
} = {
    name: "",
    description: "",
    starts_at: "",
    ends_at: "",
    organiser: "",
    location_id: null,
    is_competition: false,
    capacity: "Korlátlan",
};

const EditEventPage = () => {
    const { eventid } = useParams();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });

    if (!event) return <Loader />;
    return (
        <div className="container mx-auto">
            <h1 className="text-center text-4xl font-bold">{locale.title}</h1>
            <EventCRUD.Updater value={event} />
        </div>
    );
};

export default gated(EditEventPage, isAdmin);
