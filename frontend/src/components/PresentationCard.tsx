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
      className={`bg-gray-100 p-2 rounded-lg flex flex-col justify-between ${
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
        <h3 className="px-2 tex-lg font-bold">{presentation.name}</h3>
        <hr />
        <h4 className="px-2">{presentation.organiser}</h4>
      </div>
      <p className="px-2">{presentation.description}</p>
      <div>
        <div className="flex flew-row justify-between w-full px-2 mb-1 mt-3">
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
