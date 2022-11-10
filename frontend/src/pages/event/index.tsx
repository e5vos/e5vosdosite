import Error from "components/Error";
import EventCard from "components/EventCard";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";
import { useState, useEffect } from "react";
import { Slot } from "types/models";

const EventsPage = () => {
  const {
    data: slots,
    isLoading: isSlotsLoading,
    isFetching,
    error: slotsError,
  } = api.useGetSlotsQuery();

  const [currentSlot, setCurrentSlot] = useState(0);

  const { data: events, isFetching: isEventsFetching } = api.useGetEventsQuery(
    slots ? slots[currentSlot]?.id ?? -1 : -1
  );

  if (slotsError) return <Error code={500} />;
  if (!slots) return <Loader />;

  return (
    <div className="mx-5">
      <div className="container mx-auto">
        <h1 className="max-w-f pb-4 text-center text-4xl font-bold">
          E5N - Programok
        </h1>
        <div className="mb-4 md:flex ">
          <ButtonGroup>
            {slots.map((slot, index) => (
              <Button
                variant="secondary"
                key={index}
                disabled={index === currentSlot}
                onClick={() => setCurrentSlot(index)}
              >
                {slot.name}
              </Button>
            ))}
          </ButtonGroup>
        </div>
      </div>

      {isEventsFetching ? (
        <Loader />
      ) : (
        <div className="grid-cols-4 gap-2 md:grid">
          {events?.map((event, index) => (
            <EventCard event={event} key={index} />
          ))}
        </div>
      )}
    </div>
  );
};

export default EventsPage;
