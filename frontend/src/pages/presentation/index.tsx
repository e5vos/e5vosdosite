import { useState, useMemo, useEffect, useRef, Fragment } from "react";
import PresentationsTable from "components/PresentationsTable";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import { Presentation } from "types/models";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";
import useUser from "hooks/useUser";
import useGetPresentationSlotsQuery from "hooks/useGetPresentationSlotsQuery";
import { Transition } from "@headlessui/react";
import { Navigate, useNavigate } from "react-router-dom";
import ErrorMsgBox from "components/UIKit/ErrorMsgBox";
import { IoLocationSharp } from "react-icons/io5";

const PresentationsPage = () => {
  const [currentSlot, setcurrentSlot] = useState(0);
  const [errorShown, setErrorShown] = useState(false);
  const errorShownTimeout = useRef<ReturnType<typeof setTimeout> | null>(null);
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
    refetch: refetchEvents,
  } = api.useGetEventsQuery((slots && slots[currentSlot]?.id) ?? -1, {
    pollingInterval: 10000,
  });
  const [signUp, { isLoading: signupInProgress, error: signupError }] =
    api.useSignUpMutation();
  const [
    cancelSignup,
    { isLoading: cancelSignupInProgress, error: cancelSignupError },
  ] = api.useCancelSignUpMutation();
  const navigate = useNavigate();

  const { user } = useUser();
  const signUpAction = async (presentation: Presentation) => {
    if (signupInProgress) {
      return;
    }
    try {
      if (!user) {
        alert("Nem vagy bejelentkezve!");
        return;
      }
      if (!user.e5code) {
        alert("Nem adtad meg az E5 kódot!");
        navigate("/studentcode?next=/eloadas");
      }
      const attendance = await signUp({
        attender: user.e5code,
        event: presentation,
      }).unwrap();
      refetchSelected();
      refetchEvents();
    } catch (err) {}
  };

  const cancelSignupAction = async (presentation: Presentation) => {
    if (cancelSignupInProgress || !user) {
      return;
    }
    if (!user.e5code) {
      alert("Nem adtad meg az E5 kódot!");
      navigate("/studentcode?next=/eloadas");
    }
    try {
      if (user) {
        await cancelSignup({
          attender: user.e5code,
          event: presentation,
        }).unwrap();
        refetchSelected();
        refetchEvents();
      }
    } catch (err) {
      console.log(err);
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

  const errormsg = useMemo(() => {
    if (signupError && "status" in signupError) {
      const message = (signupError.data as any).message;
      if (!message && message === "") return "Ismeretlen hiba";
      else return message;
    }
    if (cancelSignupError && "status" in cancelSignupError) {
      const message = (cancelSignupError.data as any).message;
      if (!message && message === "") return "Ismeretlen hiba";
      else return message;
    }
  }, [signupError, cancelSignupError]);

  useEffect(() => {
    if (errormsg !== undefined) {
      setErrorShown(true);
      if (errorShownTimeout.current) clearTimeout(errorShownTimeout.current);
      errorShownTimeout.current = setTimeout(() => {
        setErrorShown(false);
      }, 3000);
    }
  }, [errormsg]);

  if (!slots || !selectedPresentations || !presentations) return <Loader />;

  const SelectField = () => {
    return (
      <div className="flex flex-1 flex-col items-stretch justify-center gap-4 text-center md:mx-3 md:flex-row md:gap-8">
        <div className="flex-1">
          <h3>Általad Választott előadás</h3>
          <div className="rounded-lg bg-green-600 p-3 ">
            {selectedPresentation?.name ?? "Még nem választottál előadást"}
          </div>
          <div className="mt-2 rounded-lg bg-goldenrod p-3 ">
            <div className="text-lg">
              <IoLocationSharp className="inline-block text-xl" />
              {selectedPresentation?.location?.name ?? "Ismeretlen hely"}
            </div>
          </div>
        </div>
        <Button
          variant="danger"
          onClick={() => {
            if (selectedPresentation) cancelSignupAction(selectedPresentation);
          }}
          disabled={!selectedPresentation || cancelSignupInProgress}
        >
          Törlés
        </Button>
      </div>
    );
  };

  return (
    <div className="mx-5">
      <div className="container mx-auto">
        <h1 className="max-w-f pb-4 text-center text-4xl font-bold">
          E5N - Előadásjelentkezés
        </h1>
        <ErrorMsgBox errorShown={errorShown} errormsg={errormsg} />
        <div className="mb-4 flex-row items-stretch justify-between  md:flex ">
          <ButtonGroup>
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
          <SelectField />
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
