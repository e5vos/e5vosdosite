import useUser from "hooks/useUser";
import { MouseEventHandler } from "react";
import { useParams } from "react-router-dom";

import { Attendance, isUserAttendance } from "types/models";

import eventAPI from "lib/api/eventAPI";
import { isOperator, isTeacher } from "lib/gates";
import Locale from "lib/locale";
import { reverseNameOrder } from "lib/util";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
  hu: {
    attendanceSheet: "Jelenléti ív",
    delete: "Törlés",
    refresh: "Frissítés",
  },
  en: {
    attendanceSheet: "Attendance sheet",
    delete: "Delete",
    refresh: "Refresh",
  },
});

const AttendancePage = () => {
  const { eventid } = useParams<{ eventid: string }>();
  const { data: event, isLoading: isEventLoading } = eventAPI.useGetEventQuery(
    Number(eventid ?? -1),
  );
  const {
    data: participantsData,
    isLoading: isParticipantsLoading,
    isFetching: isParticipantsFetching,
    refetch,
  } = eventAPI.useGetEventParticipantsQuery(Number(eventid ?? -1));

  const [deleteAttendance] = eventAPI.useCancelSignUpMutation();
  const { user } = useUser();
  const participants =
    participantsData
      ?.slice()
      .sort((a, b) =>
        reverseNameOrder(a.name).localeCompare(reverseNameOrder(b.name)),
      ) ?? [];

  const [toggleAPI, { isLoading }] = eventAPI.useToggleAttendanceMutation();

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
        isUserAttendance(attending) ? attending.id : attending.code,
      ),
      event: event,
    }).unwrap();
    refetch();
  };

  return (
    <div className="container mx-auto mt-2 ">
      <div className="mx-auto w-fit text-center">
        <h1 className="text-4xl font-bold">
          {locale.attendanceSheet} - {event?.name}
        </h1>
        <div className="mt-4">
          <ul className="mb-3 border">
            {participants?.map((attending, index) => (
              <li
                key={attending.name}
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
                    {locale.delete}
                  </Button>
                )}
              </li>
            ))}
          </ul>
          <Button onClick={() => refetch()}>{locale.refresh}</Button>
        </div>
      </div>
    </div>
  );
};

export default gated(AttendancePage, isTeacher);
