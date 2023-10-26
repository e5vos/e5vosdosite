import { IoLocationSharp } from "react-icons/io5";
import { useParams } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";
import Locale from "lib/locale";

import Error from "components/Error";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        description: "Leírás",
        organised: "Szervezte:",
    },
    en: {
        description: "Description",
        organised: "Organised by:",
    },
});

const EventManager = () => {
    const { eventid } = useParams<{ eventid: string }>();
    const { data: event, error } = eventAPI.useGetEventQuery(Number(eventid));

    if (error) return <Error code={404} />;
    if (!event) return <Loader />;
    return (
        <div className="container mx-auto">
            <div className="mx-auto text-center">
                <h1 className="text-4xl font-bold">{event.name}</h1>
                <h5 className="italic underline underline-offset-4">
                    {locale.organised} {event.organiser}
                </h5>
            </div>
            <div className="flex-row gap-4 md:flex">
                <div>
                    <img
                        src={
                            event.img_url ??
                            "https://via.placeholder.com/900x500.png"
                        }
                        className="mt-4"
                        alt={`${event.name} képe`}
                    />
                    <div className="border-spacing-163justify-center items-middle mx-auto my-4 flex w-fit rounded-lg border border-red p-3 text-4xl ">
                        <IoLocationSharp className="fill-goldenrod" />
                        <span>
                            {event.location?.name ?? "Ismeretlen helyszín"}
                        </span>
                    </div>
                </div>
                <div>
                    <h2 className="text-center text-2xl font-bold md:text-left">
                        {locale.description}
                    </h2>
                    <p>{event.description}</p>
                </div>
            </div>
        </div>
    );
};
export default EventManager;
