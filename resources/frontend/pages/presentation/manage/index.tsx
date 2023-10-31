import useGetPresentationSlotsQuery from "hooks/useGetPresentationSlotsQuery";
import useUser from "hooks/useUser";
import { useMemo, useState } from "react";

import { Presentation } from "types/models";

import adminAPI from "lib/api/adminAPI";
import eventAPI from "lib/api/eventAPI";
import { isOperator, isTeacher } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import PresentationCard from "components/PresentationCard";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        operatorInfo: "Operátor Információk",
        isPresent: "jelen van",
        isMissing: "hiányzik",
        didNotSignUp: "nem jelentkezett",
        title: "Előadások kezelése",
        search: "Keresés",
        unknown: "Ismeretlen",
    },
    en: {
        operatorInfo: "Operator info",
        isPresent: "is present",
        isMissing: "is missing",
        didNotSignUp: "did not sign up",
        title: "Manage presentations",
        search: "Search",
        unknown: "Unknown",
    },
});

const AdminCounter = ({ slotId }: { slotId: number }) => {
    const { data: notSignedUpStudents } = adminAPI.useGetFreeUsersQuery(
        { id: slotId },
        {
            pollingInterval: 5000,
        },
    );

    const { data: missingStudents } = adminAPI.useGetNotPresentUsersQuery(
        { id: slotId },
        {
            pollingInterval: 5000,
        },
    );

    const { data: presentStudents } = adminAPI.useGetPresentUsersQuery(
        { id: slotId },
        {
            pollingInterval: 5000,
        },
    );

    return (
        <div className="mx-auto mb-3 w-fit rounded-lg bg-red-500 px-4 py-4 text-center text-xl">
            <div>{locale.operatorInfo}</div>
            <div>
                {presentStudents?.length ?? locale.unknown} {locale.isPresent}
            </div>
            <div>
                {missingStudents?.length ?? locale.unknown} {locale.isMissing}
            </div>
            <div>
                {notSignedUpStudents?.length ?? locale.unknown}{" "}
                {locale.didNotSignUp}
            </div>
        </div>
    );
};

const PresentationManagePage = () => {
    const [currentSlot, setcurrentSlot] = useState(0);
    const { data: slots } = useGetPresentationSlotsQuery();
    const { data: presentations, isFetching: isEventsFetching } =
        eventAPI.useGetEventsQuery(slots?.[currentSlot]?.id ?? -1);

    const { user } = useUser();

    const [searchterm, setSearchterm] = useState("");

    const filteredpresentations = presentations?.filter(
        (presentation) =>
            presentation.name.toLowerCase().includes(searchterm) ||
            presentation.organiser.toLowerCase().includes(searchterm),
    );

    const fillAllowed = useMemo(() => user && isOperator(user), [user]);

    return (
        <div className="mx-10">
            <div className="mx-auto mt-3 h-full text-center">
                <h1 className="mb-3 text-4xl font-bold">{locale.title}</h1>
                <Form.Group>
                    <Form.Label>{locale.search}</Form.Label>
                    <Form.Control
                        className="border border-black"
                        onChange={(e) =>
                            setSearchterm(e.target.value.toLowerCase())
                        }
                    />
                </Form.Group>
                <ButtonGroup className="mx-2 my-3">
                    {slots?.map((slot, index) => (
                        <Button
                            variant="primary"
                            key={slot.name}
                            disabled={index === currentSlot}
                            onClick={() => setcurrentSlot(index)}
                        >
                            {slot.name}
                        </Button>
                    ))}
                </ButtonGroup>
            </div>
            {user && isOperator(user) && (
                <AdminCounter slotId={slots?.[currentSlot]?.id ?? -1} />
            )}
            {isEventsFetching ? (
                <Loader />
            ) : (
                <div className="gap-2 md:grid md:grid-cols-3 xl:grid-cols-4">
                    {filteredpresentations?.map((presentation) => (
                        <PresentationCard
                            key={presentation.id}
                            presentation={presentation as Presentation}
                            className="mb-3 md:mb-0"
                            fillAllowed={fillAllowed ?? false}
                        />
                    ))}
                </div>
            )}
        </div>
    );
};

export default gated(PresentationManagePage, isTeacher);
