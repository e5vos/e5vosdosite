import Gate, { gated } from "components/Gate";
import PresentationCard from "components/PresentationCard";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";
import useGetPresentationSlotsQuery from "hooks/useGetPresentationSlotsQuery";
import { api } from "lib/api";
import { isTeacher } from "lib/gates";
import { useState } from "react";

const PresentationManagePage = () => {
  const [currentSlot, setcurrentSlot] = useState(0);
  const { data: slots } = useGetPresentationSlotsQuery();
  console.log(slots);
  const {
    data: presentations,
    isLoading: isEventsLoading,
    isFetching: isEventsFetching,
  } = api.useGetEventsQuery((slots && slots[currentSlot]?.id) ?? -1);

  const [searchterm, setSearchterm] = useState("");

  const filteredpresentations = presentations?.filter((presentation) =>
    presentation.name.includes(searchterm)
  );

  return (
    <>
      <div className="mx-auto text-center my-5 h-full">
        <h1 className="text-lg font-bold">Előadások kezélese</h1>
        <Form.Group>
          <Form.Label>Keresés</Form.Label>
          <Form.Control
            className="border border-black"
            onChange={(e) => setSearchterm(e.target.value)}
          />
        </Form.Group>
        <ButtonGroup className="mx-2">
          {Array(slots?.length)
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
      </div>
      <div className="grid grid-cols-4 gap-2">
        {isEventsFetching ? (
          <Loader />
        ) : (
          filteredpresentations?.map((presentation) => (
            <PresentationCard presentation={presentation} />
          ))
        )}
      </div>
    </>
  );
};

export default gated(PresentationManagePage, isTeacher);
