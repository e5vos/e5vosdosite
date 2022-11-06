import { useState } from "react";
import PresentationsTable from "components/PresentationsTable";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import { Presentation } from "types/models";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";
import useUser from "hooks/useUser";
import useGetPresentationSlotsQuery from "hooks/useGetPresentationSlotsQuery";

const PresentationsPage = () => {
  const [currentSlot, setcurrentSlot] = useState(0);
  const { data: slots } = useGetPresentationSlotsQuery();
  const {
    data: selectedPresentations,
    isFetching: isMyPresentationsFetching,
    refetch: refetchSelected,
  } = api.useGetUsersPresentationsQuery();
  const {
    data: presentations,
    isLoading: isEventsLoading,
    isFetching: isEventsFetching,
  } = api.useGetEventsQuery((slots && slots[currentSlot]?.id) ?? -1);
  const [signUp, { isLoading: signupInProgress }] = api.useSignUpMutation();

  const { user } = useUser();
  const signUpAction = async (presentation: Presentation) => {
    console.log("SIGNUP", presentation);
    if (signupInProgress) {
      console.log("SIGNUP IN PROGRESS");
      return;
    }
    try {
      if (!user || !user.e5code) {
        alert("Please enter your code first");
        console.log(user);
        return;
      }
      const attendance = await signUp({
        attender: user.e5code,
        event: presentation,
      }).unwrap();
      console.log("ATTENDANCE", attendance);
      refetchSelected();
    } catch (err) {
      console.error("ERROR", err);
    }
  };

  if (!slots || !selectedPresentations || !presentations) return <Loader />;

  return (
    <div className="container mx-auto">
      <h1 className="text-center text-gray-800 text-5xl pb-4">
        E5N - Előadásjelentkezés
      </h1>

      <div className="flex flex-row items-center mx-auto max-w-6xl justify-center">
        <ButtonGroup className="mx-2">
          {Array(slots.length)
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
            {isMyPresentationsFetching ? (
              <Loader />
            ) : (
              selectedPresentations[currentSlot]?.name ??
              "Nincs előadás kiválasztva"
            )}
          </div>
        </div>
      </div>
      <PresentationsTable
        presentations={presentations ?? []}
        callback={selectedPresentations[currentSlot] ? undefined : signUpAction}
        disabled={signupInProgress || isMyPresentationsFetching}
        isLoading={isEventsFetching}
      />
    </div>
  );
};
export default PresentationsPage;
