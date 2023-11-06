import useUser from "hooks/useUser";
import { useMemo, useState } from "react";
import { Link } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import EventCard from "components/EventCard";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        title: "E5N - Programok",
        create: "Program hozzadása",
        search: "Keresés",
    },
    en: {
        title: "E5N - Events",
        create: "Create event",
        search: "Search",
    },
});

const EventsPage = () => {
    const { user } = useUser(false);

    const { data: events, isFetching: isEventsFetching } =
        eventAPI.useGetEventsQuery();

    const [search, setSearch] = useState("");

    const filteredEvents = useMemo(() => {
        if (!events) return [];

        return events.filter((event) => {
            return event.name.toLowerCase().includes(search.toLowerCase());
        });
    }, [events, search]);

    const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setSearch(e.target.value);
    };

    if (!events) return <Loader />;
    return (
        <div className="mx-5">
            <div className="container mx-auto">
                <h1 className="max-w-f pb-4 text-center text-4xl font-bold">
                    {locale.title}{" "}
                    {isAdmin(user) && (
                        <Link
                            to="/esemeny/uj"
                            className="my-auto align-middle text-sm"
                        >
                            <Button variant="primary">{locale.create}</Button>
                        </Link>
                    )}
                </h1>
            </div>
            <div className="mx-auto my-3 w-fit rounded-lg bg-gray-100 p-2 text-center">
                <Form.Group>
                    <Form.Label className="!mr-3">{locale.search}</Form.Label>
                    <Form.Control onChange={handleSearchChange} />
                </Form.Group>
            </div>

            {isEventsFetching ? (
                <Loader />
            ) : (
                <div className="grid-cols-4 gap-2 md:grid">
                    {filteredEvents.map((event) => (
                        <EventCard event={event} key={event.id} />
                    ))}
                </div>
            )}
        </div>
    );
};

export default EventsPage;
