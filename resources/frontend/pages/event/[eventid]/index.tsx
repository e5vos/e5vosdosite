import useEventDates from "hooks/useEventDates";
import useUser from "hooks/useUser";
import { useMemo, useRef } from "react";
import { Link, useParams } from "react-router-dom";

import { SignupType, SignupTypeType } from "types/models";

import eventAPI from "lib/api/eventAPI";
import teamAPI from "lib/api/teamAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Card from "components/UIKit/Card";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        organiser: "Szervező",
        description: "Leírás",
        times: "Időpontok",
        starts_at: "Kezdés",
        ends_at: "Befejezés",
        signup_deadline: "Jelentkezési határidő",
        location: "Helyszín",
        unknown: "Ismeretlen",
        manage: "Kezelés",
        solo: "Egyéni jelentkezés",
        singup: "Jelentkezés az eseményre",
        signup_CTA: "Jelentkezz!",
        signup_type: (type: SignupTypeType): string => {
            switch (type) {
                case SignupType.Individual:
                    return "Egyéni jelentkezés";
                case SignupType.Team:
                    return "Csapatos jelentkezés";
                case SignupType.Both:
                    return "Egyéni és csapatos jelentkezés";
            }
        },
    },
    en: {
        organiser: "Organiser",
        description: "Description",
        times: "Timetable",
        starts_at: "Starts at",
        ends_at: "Ends at",
        signup_deadline: "Signup deadline",
        location: "Location",
        unknown: "Unknown",
        manage: "Manage",
        solo: "Solo signup",
        singup: "Sign up for the event",
        signup_CTA: "Sign up!",
        signup_type: (type: SignupTypeType) => {
            switch (type) {
                case SignupType.Individual:
                    return "Individual signup";
                case SignupType.Team:
                    return "Team signup";
                case SignupType.Both:
                    return "Individual and team signup";
            }
        },
    },
});

const EventPage = () => {
    const { eventid } = useParams();
    const { user } = useUser();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });
    const { starts_at, ends_at, signup_deadline } = useEventDates(event);
    const { data: myteams } = teamAPI.useGetMyTeamsQuery();
    const [signup] = eventAPI.useSignUpMutation();

    const attenderSelect = useRef<HTMLSelectElement>(null);

    const handleSignup = async () => {
        if (!event || !attenderSelect.current?.value) return;
        await signup({ attender: attenderSelect.current.value, event });
    };

    const canSignup = useMemo(() => {
        if (!event) return false;
        if (!event.signup_deadline) return true;
        return new Date(event.signup_deadline) < new Date();
    }, [event]);

    const signUpTeams = useMemo(() => {
        if (!myteams) return [];
        return myteams.filter(
            (team) =>
                !team.attendance?.some((a) => a.pivot.event_id === event?.id),
        );
    }, [event?.id, myteams]);

    if (!event || !user) return <Loader />;

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
                <p className="text-l mt-1 italic text-gray-50">
                    {locale.signup_type(event.signup_type)}
                </p>
                {canSignup && (
                    <div className="mb-5 mt-5 rounded-lg border bg-slate-500 p-2 md:mb-0 md:border-none md:bg-inherit md:p-0">
                        <h3 className="text-center font-bold">
                            {locale.signup_CTA}
                        </h3>
                        <Form.Group className="mt-3 flex w-full flex-row gap-3">
                            <Form.Select
                                ref={attenderSelect}
                                className="flex-1 "
                            >
                                {event.signup_type !== SignupType.Team && (
                                    <option>{locale.solo}</option>
                                )}
                                {event.signup_type !== SignupType.Individual &&
                                    signUpTeams.map((team) => (
                                        <option
                                            key={team.code}
                                            value={team.code}
                                        >
                                            {team.name}
                                        </option>
                                    ))}
                            </Form.Select>
                            <Button className="w-1/4" onClick={handleSignup}>
                                {locale.signup_CTA}
                            </Button>
                        </Form.Group>
                    </div>
                )}

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
                        {starts_at?.toLocaleString("hu-HU")}
                    </p>
                    <p>
                        <strong>{locale.ends_at}</strong>:{" "}
                        {ends_at?.toLocaleString("hu-HU")}
                    </p>
                    <p>
                        <strong>{locale.signup_deadline}</strong>:{" "}
                        {signup_deadline?.toLocaleString("hu-HU")}
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
