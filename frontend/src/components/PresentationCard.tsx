import { Link } from "react-router-dom";
import { Presentation } from "types/models";
import PresentationFillDialog from "./PresentationFillDialog";
import Button from "./UIKit/Button";
import ButtonGroup from "./UIKit/ButtonGroup";
import { useState } from "react";

const PresentationCard = ({ presentation }: { presentation: Presentation }) => {
  const [isDialogOpen, setisDialogOpen] = useState(false);

  return (
    <div>
      <PresentationFillDialog
        event={presentation}
        open={isDialogOpen}
        onClose={() => {
          setisDialogOpen(false);
        }}
      />
      <div className="border border-red-500 rounded-lg flex flex-col justify-between">
        <div>
          <h3 className="px-2 tex-lg font-bold">{presentation.name}</h3>
          <hr />
          <h4 className="px-2">{presentation.organiser}</h4>
        </div>
        <p className="px-2">{presentation.description}</p>
        <div>
          <div className="flex flew-row justify-between w-full px-2">
            <div>{presentation.location?.name ?? "Ismeretlen"}</div>
            <div>
              {presentation.slot_id ?? "-"}/{presentation.id}
            </div>
            <div>?/?/{presentation.capacity ?? <>&infin;</>}</div>
          </div>
          <ButtonGroup className="w-full">
            <Button className="w-6/12">
              <Link to={`${presentation.id}`}>Jelenléti Ív</Link>
            </Button>
            <Button className="w-6/12" onClick={() => setisDialogOpen(true)}>
              Feltöltés
            </Button>
          </ButtonGroup>
        </div>
      </div>
    </div>
  );
};

export default PresentationCard;
