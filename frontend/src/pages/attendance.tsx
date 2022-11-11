import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";
import { isOperator, isTeacher } from "lib/gates";
import { useParams } from "react-router-dom";
import { Attendance, isUserAttendance } from "types/models";
import { MouseEventHandler } from "react";
import { reverseNameOrder } from "lib/util";
import useUser from "hooks/useUser";
const AttendancePage = () => {
  const { eventid } = useParams<{ eventid: string }>();
  const { data: event, isLoading: isEventLoading } = api.useGetEventQuery(
    Number(eventid ?? -1)
  );
  const {
    data: participantsData,
    isLoading: isParticipantsLoading,
    isFetching: isParticipantsFetching,
    error: participantsError,
    refetch,
  } = api.useGetEventParticipantsQuery(Number(eventid ?? -1));

  const [deleteAttendance, { isLoading: isDeleteLoading }] =
    api.useCancelSignUpMutation();
  const { user } = useUser();
  const participants =
    participantsData
      ?.slice()
      .sort((a, b) =>
        reverseNameOrder(a.name).localeCompare(reverseNameOrder(b.name))
      ) ?? [];

  const [toggleAPI, { isLoading }] = api.useToggleAttendanceMutation();

  if (isEventLoading || isParticipantsLoading || !event) return <Loader />;

  const toggle =
    (attending: Attendance): MouseEventHandler<HTMLInputElement> =>
    async (e) => {
      const target = e.currentTarget;
      e.preventDefault();

      const res = await toggleAPI(attending);
      if ("error" in res) {
        alert("Error");
      } else {
        target.checked = !target.checked;
      }
    };

  const score = (attending: Attendance, score: number) => {
    console.log(attending.name + " is now " + score);
  };

  const deleteAttendanceAction = async (attending: Attendance) => {
    await deleteAttendance({
      attender: String(
        isUserAttendance(attending) ? attending.id : attending.code
      ),
      event: event,
    }).unwrap();
    refetch();
  };

  return (
    <div className="container mx-auto mt-2 ">
      <div className="mx-auto w-fit text-center">
        <h1 className="text-4xl font-bold">Jelenléti Ív - {event?.name}</h1>
        <div className="mt-4">
          <ul className="mb-3 border">
            {participants?.map((attending, index) => (
              <li
                key={index}
                className="mx-2 my-2 flex flex-row justify-center gap-4  align-middle"
              >
                <span className="mx-4">
                  {isUserAttendance(attending)
                    ? reverseNameOrder(attending.name)
                    : attending.name}{" "}
                  {isUserAttendance(attending) && attending.ejg_class}
                </span>
                <Form.Check
                  defaultChecked={attending.pivot.is_present}
                  onClick={toggle(attending)}
                  disabled={isLoading || isParticipantsFetching}
                  className="h-5 w-5"
                />
                {user && isOperator(user) && (
                  <Button
                    variant="danger"
                    onClick={() => deleteAttendanceAction(attending)}
                  >
                    Törlés
                  </Button>
                )}
              </li>
            ))}
          </ul>
          <Button onClick={() => refetch()}>Frissítés</Button>
        </div>
      </div>
    </div>
  );
};

export default gated(AttendancePage, isTeacher);
