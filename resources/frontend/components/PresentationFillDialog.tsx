import { useEffect, useState } from "react";

import { Event, User, UserStub } from "types/models";

import adminAPI from "lib/api/adminAPI";
import eventAPI from "lib/api/eventAPI";
import Locale from "lib/locale";
import { sortByEJGClass } from "lib/util";

import Button from "./UIKit/Button";
import Dialog from "./UIKit/Dialog";
import Form from "./UIKit/Form";
import Loader from "./UIKit/Loader";

const locale = Locale({
    hu: {
        errorDuringSignup: "Hiba történt a jelentkezés során!",
        fillEvent: "Esemény feltöltése",
        search: "Keresés",
        close: "Bezárás",
        assign: "Beosztás",
    },
    en: {
        errorDuringSignup: "An error occured during signup!",
        fillEvent: "Fill event",
        search: "Search",
        close: "Close",
        assign: "Assign",
    },
});

const PresentationFillDialog = ({
    open,
    onClose,
    event: external_event,
}: {
    open: boolean;
    onClose: () => void;
    event: Event & { slot_id: number };
}) => {
    const [searchString, setSearchString] = useState("");

    const [availableStudents, setAvailableStudents] = useState<UserStub[]>([]);

    const [
        triggerGetFreeUsersQuery,
        {
            data: availableStudentsAPI,
            isFetching: isStudentListFetching,
            isError: isStudentListError,
        },
    ] = adminAPI.useLazyGetFreeUsersQuery();

    useEffect(() => {
        if (availableStudentsAPI) setAvailableStudents(availableStudentsAPI);
    }, [availableStudentsAPI]);

    const [triggerGetEventQuery, { data: event, isFetching: isEventFetching }] =
        eventAPI.useLazyGetEventQuery();

    const [APIsignUp, { isLoading: signupInProgress }] =
        eventAPI.useSignUpMutation();

    const signUp = async (student: User) => {
        try {
            console.log(student);
            await APIsignUp({
                attender: student.id,
                event: external_event,
            }).unwrap();
            setAvailableStudents((state) =>
                state.filter((s) => s.id !== student.id),
            );
            //triggerGetFreeUsersQuery({ id: external_event.slot_id });
            triggerGetEventQuery(external_event);
        } catch (err) {
            console.error(err);
            alert(locale.errorDuringSignup);
        }
    };

    useEffect(() => {
        if (open && !event) triggerGetEventQuery(external_event, true);
        if (
            open &&
            !availableStudents &&
            !isStudentListFetching &&
            !isStudentListError
        )
            triggerGetFreeUsersQuery({ id: external_event.slot_id }, true);
    }, [
        open,
        triggerGetFreeUsersQuery,
        availableStudents,
        isStudentListFetching,
        isStudentListError,
        external_event.slot_id,
        event,
        triggerGetEventQuery,
        external_event.id,
        external_event,
    ]);
    return (
        <Dialog
            open={open}
            onClose={onClose}
            title={`${locale.fillEvent} - ${event?.name}`}
            description={event?.description}
        >
            {!event ? (
                <Loader />
            ) : (
                <div>
                    <div className="mx-4 mb-4 text-center">
                        <div className="mb-3 text-4xl">
                            {event.occupancy}/
                            {event.capacity
                                ? event.capacity - event.occupancy
                                : event.occupancy}
                            /{event.capacity ?? <>&infin;</>}
                        </div>

                        <Form.Group>
                            <Form.Label>{locale.search}</Form.Label>
                            <Form.Control
                                onChange={(e) =>
                                    setSearchString(e.currentTarget.value)
                                }
                            />
                        </Form.Group>
                    </div>

                    <div className="scroller h-[500px]  overflow-auto">
                        <ul className="mx-3">
                            {!availableStudents ? (
                                <Loader />
                            ) : (
                                availableStudents
                                    .filter((student) =>
                                        (student.name + student.ejg_class)
                                            .toLowerCase()
                                            .includes(
                                                searchString.toLowerCase(),
                                            ),
                                    )
                                    .sort(sortByEJGClass)
                                    .map((student) => (
                                        <li
                                            key={student.id}
                                            className="mb-3 flex flex-row justify-between"
                                        >
                                            <span>
                                                {student.name} -{" "}
                                                {student.ejg_class}
                                            </span>
                                            <Button
                                                disabled={
                                                    signupInProgress ||
                                                    isStudentListFetching ||
                                                    isEventFetching
                                                }
                                                onClick={() => signUp(student)}
                                            >
                                                {locale.assign}
                                            </Button>
                                        </li>
                                    ))
                            )}
                        </ul>
                    </div>
                </div>
            )}
            <div className="mx-auto text-center">
                <Button onClick={onClose} variant="danger">
                    {locale.close}
                </Button>
            </div>
        </Dialog>
    );
};

export default PresentationFillDialog;
