import { useState, useMemo } from "react";
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
  } = api.useGetEventsQuery((slots && slots[currentSlot]?.id) ?? -1, {
    pollingInterval: 10000,
  });
  const [signUp, { isLoading: signupInProgress }] = api.useSignUpMutation();
  const [cancelSignup, { isLoading: cancelSignupInProgress }] =
    api.useCancelSignUpMutation();

  const { user } = useUser();
  const signUpAction = async (presentation: Presentation) => {
    if (signupInProgress) {
      return;
    }
    try {
      if (!user || !user.e5code) {
        alert("Please enter your code first");
        return;
      }
      const attendance = await signUp({
        attender: user.e5code,
        event: presentation,
      }).unwrap();
      refetchSelected();
    } catch (err) {}
  };

  const cancelSignupAction = async (presentation: Presentation) => {
    if (cancelSignupInProgress) {
      return;
    }
    try {
      if (!user || !user.e5code) {
        alert("Please enter your code first");
        return;
      }
      await cancelSignup({
        attender: user.e5code,
        event: presentation,
      }).unwrap();
      refetchSelected();
    } catch (err) {
      alert("Jelentkezés törlése sikertelen");
    }
  };

  const selectedPresentation = useMemo(
    () =>
      slots &&
      selectedPresentations?.find(
        (presentation) => presentation.slot_id === slots[currentSlot].id
      ),
    [currentSlot, selectedPresentations, slots]
  );

  if (!slots || !selectedPresentations || !presentations) return <Loader />;

  return (
    <div className="mx-5">
      <div className="container mx-auto">
        <h1 className="text-center font-bold text-4xl max-w-f pb-4">
          E5N - Előadásjelentkezés
        </h1>
        <div className="md:flex flex-row items-center mb-4 mx-auto max-w-6xl justify-center">
          <ButtonGroup className="mx-2">
            {slots.map((slot, index) => (
              <Button
                variant="secondary"
                key={index}
                disabled={index === currentSlot}
                onClick={() => setcurrentSlot(index)}
              >
                {slot.name}
              </Button>
            ))}
          </ButtonGroup>
          <div className="md:flex flex-row items-center text-center">
            <div>Általad választott előadás:</div>
            <div className="mx-2 px-6 bg-emerald-700 py-2 rounded-2xl">
              {isMyPresentationsFetching ? (
                <Loader />
              ) : selectedPresentation ? (
                <>
                  {selectedPresentation.name} -{" "}
                  <Button
                    variant="danger"
                    onClick={() => cancelSignupAction(selectedPresentation)}
                    disabled={cancelSignupInProgress}
                  >
                    Törlés
                  </Button>
                </>
              ) : (
                "Nincs előadás kiválasztva"
              )}
            </div>
          </div>
        </div>
        <PresentationsTable
          presentations={(presentations as Presentation[]) ?? []}
          callback={selectedPresentation ? undefined : signUpAction}
          disabled={
            signupInProgress || isMyPresentationsFetching || isEventsFetching
          }
          isLoading={isEventsLoading}
        />
      </div>
    </div>
  );
};
export default PresentationsPage;
