import useEventDates from "hooks/useEventDates";
import useUser from "hooks/useUser";
import { useState } from "react";
import { useParams } from "react-router-dom";
import { Link } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";
import { isAdmin, isOrganiser, isScanner } from "lib/gates";
import Locale from "lib/locale";

import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Card from "components/UIKit/Card";
import Dialog from "components/UIKit/Dialog";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        organiser: "Szervező",
        description: "Leírás",
        times: "Időpontok",
        starts_at: "Kezdés",
        ends_at: "Befejezés",
        location: "Helyszín",
        unknown: "Ismeretlen",
        edit: "Szerkesztés",
        delete: "Törlés",
        scanner: "Résztvevők kódjának beolvasása",
        permissions: "Jogosultságok kezelése",
        close_signup: "Jelentkezés lezárása",
        are_you_sure: {
            index: "Biztosan szeretnéd",
            delete: "törölni az eseményt?",
            close_signup: "lezárni a jelentkezést?",
            yes: "Igen, biztos vagyok",
        },
    },
    en: {
        organiser: "Organiser",
        description: "Description",
        times: "Timetable",
        starts_at: "Starts at",
        ends_at: "Ends at",
        location: "Location",
        unknown: "Unknown",
        edit: "Edit",
        delete: "Delete",
        scanner: "Scan attendee codes",
        permissions: "Manage permissions",
        close_signup: "Close signup",
        are_you_sure: {
            index: "Are you sure you want to",
            delete: "delete the event?",
            close_signup: "close the registration?",
            yes: "Yes, I'm sure",
        },
    },
});

const ManageEventPage = () => {
    const { eventid } = useParams<{ eventid: string }>();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });
    const [deleteDialog, setDeleteDialog] = useState(false);
    const [deleteEvent] = eventAPI.useDeleteEventMutation();
    const [closeSignupDialog, setCloseSignupDialog] = useState(false);
    const [closeSignup] = eventAPI.useCloseSignUpMutation();
    const { user } = useUser();

    const { now, starts_at, ends_at, signup_deadline } = useEventDates(event);

    if (!event) return <Loader />;
    if (!user) return <Loader />;

    const isUserOrganiser = isOrganiser(event)(user);
    const isUserScanner = isScanner(event)(user);

    const isEventSignupDateStillRelevant = signup_deadline
        ? now < signup_deadline
        : false;

    return (
        <>
            <Dialog
                title={
                    locale.are_you_sure.index + " " + locale.are_you_sure.delete
                }
                open={deleteDialog}
                onClose={() => setDeleteDialog(false)}
            >
                <Button
                    onClick={async () => {
                        await deleteEvent({
                            id: Number(eventid),
                        });
                        setDeleteDialog(false);
                    }}
                    variant="danger"
                >
                    {locale.are_you_sure.yes}
                </Button>
            </Dialog>
            <Dialog
                title={
                    locale.are_you_sure.index +
                    " " +
                    locale.are_you_sure.close_signup
                }
                open={closeSignupDialog}
                onClose={() => setCloseSignupDialog(false)}
            >
                <Button
                    onClick={async () => {
                        await closeSignup(event);
                        setCloseSignupDialog(false);
                    }}
                    variant="danger"
                >
                    {locale.are_you_sure.yes}
                </Button>
            </Dialog>
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
                    <ButtonGroup className="mt-6 !block w-full sm:hidden">
                        {(isAdmin(user) ||
                            isUserOrganiser ||
                            isUserScanner) && (
                            <Link
                                className="w-full"
                                to={`/esemeny/${event.id}/kezel/scanner`}
                            >
                                <Button variant="primary" className="!mb-2">
                                    {locale.scanner}
                                </Button>
                            </Link>
                        )}
                        {(isAdmin(user) || isUserOrganiser) && (
                            <Link
                                className="w-full"
                                to={`/esemeny/${event.id}/kezel/szerkeszt`}
                            >
                                <Button className="!mb-2" variant="secondary">
                                    {locale.edit}
                                </Button>
                            </Link>
                        )}
                        {isAdmin(user) && (
                            <Button
                                className="!mb-2 !rounded-md text-white"
                                variant="danger"
                                onClick={() => setDeleteDialog(true)}
                            >
                                {locale.delete}
                            </Button>
                        )}
                        {(isAdmin(user) || isUserOrganiser) && (
                            <Link
                                className="w-full"
                                to={`/esemeny/${event.id}/kezel/jogosultsagok`}
                            >
                                <Button
                                    className="!mb-2 text-white"
                                    variant="info"
                                >
                                    {locale.permissions}
                                </Button>
                            </Link>
                        )}
                        {isEventSignupDateStillRelevant &&
                            (isAdmin(user) || isUserOrganiser) && (
                                <Button
                                    className="!mb-2 !rounded-md text-white"
                                    variant="warning"
                                    onClick={() => setCloseSignupDialog(true)}
                                >
                                    {locale.close_signup}
                                </Button>
                            )}
                    </ButtonGroup>
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
                    </Card>
                    <Card title={locale.location} className="!bg-slate-500">
                        {event.location?.name ?? locale.unknown}
                    </Card>
                </div>
                <ButtonGroup className="mt-2  !hidden w-full sm:block">
                    {(isAdmin(user) || isUserOrganiser || isUserScanner) && (
                        <Link
                            className="w-full"
                            to={`/esemeny/${event.id}/kezel/scanner`}
                        >
                            <Button variant="primary" className="!mb-2">
                                {locale.scanner}
                            </Button>
                        </Link>
                    )}
                    {(isAdmin(user) || isUserOrganiser) && (
                        <Link
                            className="w-full"
                            to={`/esemeny/${event.id}/kezel/szerkeszt`}
                        >
                            <Button className="!mb-2" variant="secondary">
                                {locale.edit}
                            </Button>
                        </Link>
                    )}
                    {isAdmin(user) && (
                        <Button
                            className="!mb-2 !rounded-md text-white"
                            variant="danger"
                            onClick={() => setDeleteDialog(true)}
                        >
                            {locale.delete}
                        </Button>
                    )}
                    {(isAdmin(user) || isUserOrganiser) && (
                        <Link
                            className="w-full"
                            to={`/esemeny/${event.id}/kezel/jogosultsagok`}
                        >
                            <Button className="!mb-2 text-white" variant="info">
                                {locale.permissions}
                            </Button>
                        </Link>
                    )}
                    {isEventSignupDateStillRelevant &&
                        (isAdmin(user) || isUserOrganiser) && (
                            <Button
                                className="!mb-2 !rounded-md text-white"
                                variant="warning"
                                onClick={() => setCloseSignupDialog(true)}
                            >
                                {locale.close_signup}
                            </Button>
                        )}
                </ButtonGroup>
            </div>
        </>
    );
};
export default ManageEventPage;
