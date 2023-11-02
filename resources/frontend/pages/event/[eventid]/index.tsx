import useUser from "hooks/useUser";
import { Link, useParams } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Card from "components/UIKit/Card";
import Loader from "components/UIKit/Loader";

const EventPage = () => {
    const { eventid } = useParams();
    const { user } = useUser();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });

    const locale = Locale({
        hu: {
            organiser: "Szervező",
            description: "Leírás",
            times: "Időpontok",
            starts_at: "Kezdés",
            ends_at: "Befejezés",
            location: "Helyszín",
            unknown: "Ismeretlen",
            manage: "Kezelés",
        },
        en: {
            organiser: "Organiser",
            description: "Description",
            times: "Timetable",
            starts_at: "Starts at",
            ends_at: "Ends at",
            location: "Location",
            unknown: "Unknown",
            manage: "Manage",
        },
    });

    if (!event) return <Loader />;
    if (!user) return <Loader />;
    return (
        <div className="mx-2 mt-4 grid-cols-3 gap-3 lg:mx-12 lg:grid">
            <div className="col-span-1">
                {event.img_url && (
                    <img
                        src={event.img_url}
                        alt={event.name}
                        className="mb-2 w-auto rounded-lg"
                    />
                )}
                <h1 className="text-4xl font-bold">{event.name}</h1>
                <h2 className="mt-1 text-xl">
                    {locale.organiser}: {event.organiser}
                </h2>
                {isAdmin(user) && (
                    <ButtonGroup className="mt-6 !block w-full sm:hidden">
                        <Link
                            className="w-full"
                            to={`/esemeny/${event.id}/kezel`}
                        >
                            <Button className="text-white" variant="info">
                                {locale.manage}
                            </Button>
                        </Link>
                    </ButtonGroup>
                )}
            </div>
            <div className="col-span-2 !mt-0 sm:mt-2">
                <Card title={locale.description} className="!bg-slate-500">
                    <p>{event.description}</p>
                </Card>
                <Card title={locale.times} className="!bg-slate-500">
                    <p>
                        <strong>{locale.starts_at}</strong>:{" "}
                        {new Date(event.starts_at).toLocaleString("hu-HU")}
                    </p>
                    <p>
                        <strong>{locale.ends_at}</strong>:{" "}
                        {new Date(event.ends_at).toLocaleString("hu-HU")}
                    </p>
                </Card>
                <Card title={locale.location} className="!bg-slate-500">
                    {event.location?.name ?? locale.unknown}
                </Card>
            </div>
            {isAdmin(user) && (
                <ButtonGroup className="mt-2  !hidden w-full sm:block">
                    <Link className="w-full" to={`/esemeny/${event.id}/kezel`}>
                        <Button className="text-white" variant="info">
                            {locale.manage}
                        </Button>
                    </Link>
                </ButtonGroup>
            )}
        </div>
    );
};

export default EventPage;
