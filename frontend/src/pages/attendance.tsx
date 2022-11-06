import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";
import { isTeacher } from "lib/gates";
import { useParams } from "react-router-dom";
import { Attendance } from "types/models";
import { MouseEventHandler } from "react";
const AttendancePage = () => {
  const { eventid } = useParams<{ eventid: string }>();
  const { data: event, isLoading: isEventLoading } = api.useGetEventQuery(
    eventid ?? "-1"
  );
  const {
    data: participantsData,
    isLoading: isParticipantsLoading,
    isFetching: isParticipantsFetching,
    error: participantsError,
    refetch,
  } = api.useGetEventParticipantsQuery(eventid ?? "");

  const participants = participantsData
    ?.slice()
    .sort((a, b) => (a.name > b.name ? 1 : -1));

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

  return (
    <div className="container mx-auto mt-2 ">
      <div className="mx-auto text-center w-fit">
        <h1 className="text-4xl font-bold">Jelenléti Ív - {event?.name}</h1>
        <div className="mt-4">
          <ul className="border mb-3">
            {participants?.map((attending, index) => (
              <li
                key={index}
                className="flex flex-row justify-between mx-2 my-2  align-middle"
              >
                <span className="mx-4">{attending.name}</span>
                <Form.Check
                  defaultChecked={attending.pivot.is_present}
                  onClick={toggle(attending)}
                  disabled={isLoading || isParticipantsFetching}
                  className="h-5 w-5"
                />
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
