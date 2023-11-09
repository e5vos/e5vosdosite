import { useParams } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import EventCRUD from "components/Event/CRUD";
import { gated } from "components/Gate";
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

const EditEventPage = () => {
    const { eventid } = useParams();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });

    if (!event) return <Loader />;
    return (
        <div className="container mx-auto">
            <h1 className="text-center text-4xl font-bold">{locale.title}</h1>
            <EventCRUD.Updater value={event} initialLocation={event.location} />
        </div>
    );
};

export default gated(EditEventPage, isAdmin);
