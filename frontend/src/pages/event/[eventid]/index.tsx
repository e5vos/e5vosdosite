import { useParams } from "react-router-dom";

const EventPage = () => {
    const { eventid } = useParams();
    return (
        <div>
            <h1>{eventid}</h1>
        </div>
    );
}



export default EventPage;