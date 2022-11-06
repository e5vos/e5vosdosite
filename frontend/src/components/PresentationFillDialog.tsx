import { Dialog } from "@headlessui/react";
import { Event, User } from "types/models";
import { useState, useEffect } from "react";
import Button from "./UIKit/Button";
import { api } from "lib/api";
import Loader from "./UIKit/Loader";
import Form from "./UIKit/Form";

const PresentationFillDialog = ({
  open,
  onClose,
  event,
}: {
  open: boolean;
  onClose: () => void;
  event: Event & { slot_id: number };
}) => {
  const [searchString, setSearchString] = useState("");

  const [
    trigger,
    {
      data: availableStudents,
      isFetching: isStudentListFetching,
      isError: isStudentListError,
    },
  ] = api.useLazyGetFreeUsersQuery();

  const [APIsignUp, { isLoading: signupInProgress, isError: isSignupError }] =
    api.useSignUpMutation();

  const signUp = async (student: User) => {
    try {
      const attendance = await APIsignUp({
        attender: student.e5code,
        event: event,
      }).unwrap();
      trigger(event.slot_id);
    } catch (err) {}
  };

  useEffect(() => {
    if (
      open &&
      !availableStudents &&
      !isStudentListFetching &&
      !isStudentListError
    )
      trigger(event.slot_id, true);
  }, [
    open,
    trigger,
    availableStudents,
    isStudentListFetching,
    isStudentListError,
    event.slot_id,
  ]);

  return (
    <Dialog open={open} onClose={onClose} className="relative z-50">
      <div className="fixed inset-0 bg-slate-500/50" aria-hidden="true" />

      <div className="fixed inset-0 flex items-center justify-center p-4">
        <Dialog.Panel className="mx-auto max-w-sm rounded bg-white">
          <Dialog.Title className="text-lg font-bold">
            Esemény feltöltése
          </Dialog.Title>
          <Dialog.Description>Lorem ipsum</Dialog.Description>

          <Form.Group>
            <Form.Label>Keresés</Form.Label>
            <Form.Control
              onChange={(e) => setSearchString(e.currentTarget.value)}
            />
          </Form.Group>

          <div className=" max-h-[500px] overflow-auto">
            <ul>
              {!availableStudents ? (
                <Loader />
              ) : (
                availableStudents
                  .filter((student) => student.name.includes(searchString))
                  .map((student, index) => (
                    <li
                      key={index}
                      className="mb-3 flex flex-row justify-between"
                    >
                      <span>
                        {student.name} - {student.ejg_class}
                      </span>
                      <Button
                        disabled={signupInProgress}
                        onClick={() => signUp(student)}
                      >
                        Beosztás
                      </Button>
                    </li>
                  ))
              )}
            </ul>
          </div>

          <Button onClick={onClose} variant="danger">
            Bezárás
          </Button>
        </Dialog.Panel>
      </div>
    </Dialog>
  );
};

export default PresentationFillDialog;
