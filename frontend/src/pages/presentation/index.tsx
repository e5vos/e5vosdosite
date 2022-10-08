import {useState} from "react";
import PresentationSignup from "components/PresentationSignup";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";

const PresentationsPage = () => {
  const [currentSlot, setcurrentSlot] = useState(0)
  const slotCount = 3;
  return (
    <div className="container mx-auto">
      <h1 className="text-center text-gray-800 text-5xl pb-4">
        E5N - Előadásjelentkezés
      </h1>

      <div className="flex flex-row items-center mx-auto max-w-6xl justify-center">
        <ButtonGroup className="mx-2">
          {Array(slotCount)
            .fill(null)
            .map((_, index) => (
              <Button key={index} disabled={index === currentSlot} onClick={() => setcurrentSlot(index)}>{index + 1}.előadássáv</Button>
            ))}
        </ButtonGroup>

        <div className="flex flex-row items-center">
          <div>Általad választott előadás:</div>
          <div className="mx-2 px-6 bg-emerald-700 py-2 rounded-2xl">
            Aliquip nulla ipsum culpa sunt anim
          </div>
        </div>
      </div>
      <PresentationSignup presentations={[]} />
    </div>
  );
};
export default PresentationsPage;
