import { Link } from "react-router-dom";
import { Presentation } from "types/models";
import Button from "./UIKit/Button";
import ButtonGroup from "./UIKit/ButtonGroup";

const PresentationCard = ({ presentation }: { presentation: Presentation }) => {
  return (
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
            {presentation.slot.id}/{presentation.id}
          </div>
          <div>?/?/{presentation.capacity ?? <>&infin;</>}</div>
        </div>

        <ButtonGroup className="w-full">
          <Button className="w-6/12">
            <Link to={`${presentation.id}`}>Jelenléti Ív</Link>
          </Button>
          <Button className="w-6/12">Feltöltés</Button>
        </ButtonGroup>
      </div>
    </div>
  );
};

export default PresentationCard;
