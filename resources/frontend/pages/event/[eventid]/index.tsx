import { useParams } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";

import EventCRUD from "components/Event/CRUD";
import Loader from "components/UIKit/Loader";

const EventPage = () => {
    const { eventid } = useParams();

    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });
    if (!event) return <Loader />;
    return <EventCRUD.Reader value={event} />;
};

export default EventPage;
