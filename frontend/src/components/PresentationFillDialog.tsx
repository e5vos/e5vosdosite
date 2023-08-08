import { Dialog } from "@headlessui/react";
import { useEffect, useState } from "react";

import { Event, User } from "types/models";

import { api } from "lib/api";
import { sortByEJGClass } from "lib/util";

import Button from "./UIKit/Button";
import Form from "./UIKit/Form";
import Loader from "./UIKit/Loader";

const PresentationFillDialog = ({
  open,
  onClose,
  event: external_event,
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

  const [triggerEvent, { data: event, isFetching: isEventFetching }] =
    api.useLazyGetEventQuery();

  const [APIsignUp, { isLoading: signupInProgress }] = api.useSignUpMutation();

  const signUp = async (student: User) => {
    try {
      console.log(student);
      await APIsignUp({
        attender: student.id,
        event: external_event,
      }).unwrap();
      trigger(external_event.slot_id);
      triggerEvent(external_event.id);
    } catch (err) {
      console.error(err);
      alert("Hiba történt a jelentkezés során!");
    }
  };

  useEffect(() => {
    if (open && !event) triggerEvent(external_event.id, true);
    if (
      open &&
      !availableStudents &&
      !isStudentListFetching &&
      !isStudentListError
    )
      trigger(external_event.slot_id, true);
  }, [
    open,
    trigger,
    availableStudents,
    isStudentListFetching,
    isStudentListError,
    external_event.slot_id,
    event,
    triggerEvent,
    external_event.id,
  ]);
  return (
    <Dialog open={open} onClose={onClose} className="relative z-50">
      <div className="fixed inset-0 bg-gray-500/50" aria-hidden="true" />

      <div className="fixed inset-0 flex items-center justify-center p-4">
        <Dialog.Panel className="mx-auto max-w-lg rounded-3xl border-8 border-gray-800/50 bg-gray-100 p-3 shadow-2xl  shadow-gray-800">
          {!event ? (
            <Loader />
          ) : (
            <div>
              <div className="mx-4 mb-4 text-center">
                <Dialog.Title className="text-lg font-bold">
                  Esemény feltöltése - {event.name}
                </Dialog.Title>
                <Dialog.Description className="mb-3 text-justify">
                  {event.description}
                </Dialog.Description>

                <div className="mb-3 text-4xl">
                  {event.occupancy}/
                  {event.capacity
                    ? event.capacity - event.occupancy
                    : event.occupancy}
                  /{event.capacity ?? <>&infin;</>}
                </div>

                <Form.Group>
                  <Form.Label>Keresés</Form.Label>
                  <Form.Control
                    onChange={(e) => setSearchString(e.currentTarget.value)}
                  />
                </Form.Group>
              </div>

              <div className="scroller h-[500px]  overflow-auto">
                <ul className="mx-3">
                  {!availableStudents ? (
                    <Loader />
                  ) : (
                    availableStudents
                      .filter((student) =>
                        (student.name + student.ejg_class)
                          .toLowerCase()
                          .includes(searchString.toLowerCase())
                      )
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
                            disabled={
                              signupInProgress ||
                              isStudentListFetching ||
                              isEventFetching
                            }
                            onClick={() => signUp(student)}
                          >
                            Beosztás
                          </Button>
                        </li>
                      ))
                  )}
                </ul>
              </div>
            </div>
          )}
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
