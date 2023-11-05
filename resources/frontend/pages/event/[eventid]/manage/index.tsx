import { useParams } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";

import EventCRUD from "components/Event/CRUD";
import Loader from "components/UIKit/Loader";

const ManageEventPage = () => {
    const { eventid } = useParams<{ eventid: string }>();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });
    if (!event) return <Loader />;
    return <EventCRUD.Reader value={event} />;
};
export default ManageEventPage;
