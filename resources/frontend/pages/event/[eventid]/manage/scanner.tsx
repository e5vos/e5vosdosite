import useConfirm, { ConfirmDialogProps } from "hooks/useConfirm";
import useScannerHandler from "hooks/useScannerHandler";
import useUser from "hooks/useUser";
import { useCallback, useEffect, useState } from "react";
import { QrReader } from "react-qr-reader";
import { useParams } from "react-router-dom";

import { TeamMember, isUserAttendance } from "types/models";

import eventAPI from "lib/api/eventAPI";
import Locale from "lib/locale";

import Error, { HTTPErrorCode } from "components/Error";
import Button from "components/UIKit/Button";
import Dialog from "components/UIKit/Dialog";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        scanner: "QR Olvasó",
    },
    en: {
        scanner: "QR Scanner",
    },
});

const useMemberConfigDialog = (selectedMember: TeamMember | null) =>
    useCallback(
        ({ handleConfirm, handleCancel }: ConfirmDialogProps) => {
            if (!selectedMember) return <></>;
            return (
                <Dialog title={"Csapattag jelenléte"} closable={false}>
                    <div>
                        {selectedMember.name} - {selectedMember.ejg_class} jelen
                        van?
                    </div>
                    <div>
                        <Button onClick={handleConfirm}>Confirm</Button>
                        <Button variant="danger" onClick={handleCancel}>
                            Cancel
                        </Button>
                    </div>
                </Dialog>
            );
        },
        [selectedMember],
    );

const Scanner = () => {
    const { eventid } = useParams();
    const { data: event, error } = eventAPI.useGetEventQuery({
        id: Number(eventid),
    });

    const [errorMessage, setErrorMessage] = useState<string | null>(null);
    const [successMessage, setSuccessMessage] = useState<string | null>(null);

    const [selectedMember, setSelectedMember] = useState<null | TeamMember>(
        null,
    );
    const memberConfigDialogTemplate = useMemberConfigDialog(selectedMember);
    const [MemberConfirmDialog, confirmMember] = useConfirm(
        memberConfigDialogTemplate,
    );

    const scan = useScannerHandler({
        event: event ?? { id: -1 },
        onSuccess: (attendance) => {
            setSuccessMessage(
                `${attendance.name} - ${
                    isUserAttendance(attendance)
                        ? `(${attendance.ejg_class})`
                        : ""
                } jelenlét rögzítve`,
            );
            setTimeout(() => setSuccessMessage(null), 2000);
        },
        onError: (error) => {
            setErrorMessage(error);
            setTimeout(() => setErrorMessage(null), 2000);
        },
        teamMemberPrompt: async (member) => {
            setSelectedMember(member);
            return await confirmMember();
        },
    });
    useEffect(() => {
        (window as any).scan = scan;
    }, [scan]);
    const { user } = useUser();

    if (error && "status" in error)
        return <Error code={error.status as HTTPErrorCode} />;
    if (!event || !user) return <Loader />;

    return (
        <div className="container mx-auto">
            <div className="text-center">
                <h1 className=" text-4xl font-bold">
                    {event.name} - {locale.scanner}
                </h1>
                <h3>
                    {user.name} - {user?.ejg_class}
                </h3>
            </div>
            <div>{errorMessage ?? successMessage}</div>
            <MemberConfirmDialog />
            <QrReader
                onResult={async (result, error) => {
                    if (result) {
                        await scan(result.getText());
                        setSelectedMember(null);
                    }
                }}
                constraints={{ facingMode: "environment" }}
                className="mx-auto max-h-[1/2] max-w-4xl hover:opacity-75"
            />
        </div>
    );
};
export default Scanner;
