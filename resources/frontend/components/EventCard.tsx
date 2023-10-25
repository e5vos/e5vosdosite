import { useNavigate } from "react-router-dom";

import { Event } from "types/models";

import Locale from "lib/locale";

import Button from "./UIKit/Button";
import ButtonGroup from "./UIKit/ButtonGroup";

const locale = Locale({
    hu: "Esemény megtekintése",
    en: "View event",
});

const EventCard = ({
    event,
    className,
}: {
    event: Event;
    className?: string;
}) => {
    const navigate = useNavigate();
    return (
        <div
            className={`mb-3 flex flex-col justify-between rounded-lg bg-gray-100 p-2 ${
                className ?? ""
            }`}
        >
            <div>
                <h3 className="tex-lg px-2 font-bold">{event.name}</h3>
                <hr />
                <h4 className="px-2">{event.organiser}</h4>
            </div>
            <p className="px-2">{event.description}</p>
            <div>
                <div className="flew-row mb-1 mt-3 flex w-full justify-between px-2">
                    <div>{event.location?.name ?? "Ismeretlen"}</div>
                    <div className="mx-2">
                        {event.slot_id ?? "-"}/{event.id}
                    </div>
                </div>
            </div>
            <ButtonGroup className="w-full">
                <Button variant="info" onClick={() => navigate(`${event.id}`)}>
                    {locale}
                </Button>
            </ButtonGroup>
        </div>
    );
};

export default EventCard;
