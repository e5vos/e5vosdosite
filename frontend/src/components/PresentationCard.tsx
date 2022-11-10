import { useNavigate } from "react-router-dom";
import { Presentation } from "types/models";
import PresentationFillDialog from "./PresentationFillDialog";
import Button from "./UIKit/Button";
import ButtonGroup from "./UIKit/ButtonGroup";
import { useState } from "react";

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
    <div
      className={`mb-3 flex flex-col justify-between rounded-lg bg-gray-100 p-2 ${
        className ?? ""
      }`}
    >
      {fillAllowed && (
        <PresentationFillDialog
          event={presentation}
          open={isDialogOpen}
          onClose={() => {
            setisDialogOpen(false);
          }}
        />
      )}
      <div>
        <h3 className="tex-lg px-2 font-bold">{presentation.name}</h3>
        <hr />
        <h4 className="px-2">{presentation.organiser}</h4>
      </div>
      <p className="px-2">{presentation.description}</p>
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
            Jelenléti Ív
          </Button>
          {fillAllowed && (
            <Button
              className="w-6/12"
              variant="danger"
              onClick={() => setisDialogOpen(true)}
            >
              Feltöltés
            </Button>
          )}
        </ButtonGroup>
      </div>
    </div>
  );
};

export default PresentationCard;
