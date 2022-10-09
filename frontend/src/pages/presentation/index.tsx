import { useState } from "react";
import PresentationsTable from "components/PresentationsTable";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import routeSwitcher from "lib/route";
import { Presentation } from "types/models";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";

const slotCount = 3;

const PresentationsPage = () => {
  const [currentSlot, setcurrentSlot] = useState(0);
  
  const { data: selectedPresentations } = api.useGetUsersPresentationsQuery() ;
  const { data: presentations, } = api.useGetEventsQuery(1);

  const signUpAction = async (presentation: Presentation) => {
    console.log("SIGNUP", presentation);
    let newSelectedPresentations = selectedPresentations;
    newSelectedPresentations![currentSlot] = presentation;
    // dispatch)
    
  };

  if (!selectedPresentations || !presentations) return <Loader />;

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
              <Button
                variant="secondary"
                key={index}
                disabled={index === currentSlot}
                onClick={() => setcurrentSlot(index)}
              >
                {index + 1}.előadássáv
              </Button>
            ))}
        </ButtonGroup>
        <div className="flex flex-row items-center">
          <div>Általad választott előadás:</div>
          <div className="mx-2 px-6 bg-emerald-700 py-2 rounded-2xl">
            {selectedPresentations[currentSlot]?.name ??
              "Nincs előadás kiválasztva"}
          </div>
        </div>
      </div>
      <PresentationsTable
        presentations={presentations ?? []}
        callback={signUpAction}
      />
    </div>
  );
};
export default PresentationsPage;
