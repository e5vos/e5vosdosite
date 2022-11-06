import { Dialog } from "@headlessui/react";
import { Event, User } from "types/models";
import { useState, useEffect } from "react";
import Button from "./UIKit/Button";
import { api } from "lib/api";
import Loader from "./UIKit/Loader";
import Form from "./UIKit/Form";
import { sortByEJGClass } from "lib/util";

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

  const [APIsignUp, { isLoading: signupInProgress }] = api.useSignUpMutation();

  const signUp = async (student: User) => {
    try {
      console.log(student);
      await APIsignUp({
        attender: student.id,
        event: event,
      }).unwrap();
      trigger(event.slot_id);
    } catch (err) {
      console.error(err);
      alert("Hiba történt a jelentkezés során!");
    }
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
      <div className="fixed inset-0 bg-gray-500/50" aria-hidden="true" />

      <div className="fixed inset-0 flex items-center justify-center p-4">
        <Dialog.Panel className="mx-auto max-w-lg rounded-3xl bg-gray-100 p-3 shadow-2xl shadow-gray-800 border-gray-800/50  border-8">
          <div className="text-center mb-4 mx-4">
            <Dialog.Title className="text-lg font-bold">
              Esemény feltöltése - {event.name}
            </Dialog.Title>
            <Dialog.Description className="text-justify mb-3">
              {event.description}
            </Dialog.Description>
            <Form.Group>
              <Form.Label>Keresés</Form.Label>
              <Form.Control
                onChange={(e) => setSearchString(e.currentTarget.value)}
              />
            </Form.Group>
          </div>

          <div className="h-[500px] overflow-auto  scroller">
            <ul className="mx-3">
              {!availableStudents ? (
                <Loader />
              ) : (
                availableStudents
                  .filter((student) => student.name.includes(searchString))
                  .sort(sortByEJGClass)
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

          <div className="mx-auto text-center">
            <Button onClick={onClose} variant="danger">
              Bezárás
            </Button>
          </div>
        </Dialog.Panel>
      </div>
    </Dialog>
  );
};

export default PresentationFillDialog;
