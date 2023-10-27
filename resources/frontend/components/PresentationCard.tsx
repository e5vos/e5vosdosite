import { useState } from "react";
import { useNavigate } from "react-router-dom";

import { Presentation } from "types/models";

import Locale from "lib/locale";

import PresentationFillDialog from "./PresentationFillDialog";
import Button from "./UIKit/Button";
import ButtonGroup from "./UIKit/ButtonGroup";
import Card from "./UIKit/Card";

const locale = Locale({
    hu: {
        attendance_sheet: "Jelenléti Ív",
        fill: "Kitöltés",
        edit: "Szerkesztés",
    },
    en: {
        attendance_sheet: "Attendance Sheet",
        fill: "Fill",
        edit: "Edit",
    },
});

const PresentationCard = ({
    presentation,
    className,
    fillAllowed = false,
}: {
    presentation: Presentation;
    className?: string;
    fillAllowed: boolean;
}) => {
    const [isDialogOpen, setisDialogOpen] = useState(false);
    const navigate = useNavigate();
    return (
        <Card
            title={presentation.name}
            subtitle={presentation.organiser}
            buttonBar={
                <div>
                    <div className="flew-row mb-1 mt-3 flex w-full justify-between px-2">
                        <div>{presentation.location?.name ?? "Ismeretlen"}</div>
                        <div className="mx-2">
                            {presentation.slot_id ?? "-"}/{presentation.id}
                        </div>
                        <div>
                            {presentation.occupancy}/
                            {presentation.capacity
                                ? presentation.capacity - presentation.occupancy
                                : presentation.occupancy}
                            /{presentation.capacity ?? <>&infin;</>}
                        </div>
                    </div>
                    <ButtonGroup className="w-full">
                        <Button
                            variant="success"
                            onClick={() => navigate(presentation.id.toString())}
                        >
                            {locale.attendance_sheet}
                        </Button>
                        {fillAllowed && (
                            <Button
                                className="w-6/12"
                                variant="danger"
                                onClick={() => setisDialogOpen(true)}
                            >
                                {locale.fill}
                            </Button>
                        )}
                        {fillAllowed && (
                            <Button
                                onClick={() =>
                                    navigate(
                                        `/esemeny/${presentation.id}/kezel/szerkeszt`,
                                    )
                                }
                            >
                                {locale.edit}
                            </Button>
                        )}
                    </ButtonGroup>
                </div>
            }
        >
            <p>{presentation.description}</p>
            <div>
                {fillAllowed && (
                    <PresentationFillDialog
                        event={presentation}
                        open={isDialogOpen}
                        onClose={() => {
                            setisDialogOpen(false);
                        }}
                    />
                )}
            </div>
        </Card>
    );
};

export default PresentationCard;
