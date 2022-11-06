import { fetchBaseQuery } from "@reduxjs/toolkit/dist/query";
import Error from "components/Error";
import Gate, { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";
import useGate from "hooks/useGate";
import useUser from "hooks/useUser";
import { api } from "lib/api";
import { isTeacher } from "lib/gates";
import { useParams } from "react-router-dom";
import {
  Attendance,
  isUser,
  isUserAttendance,
  isUserAttendancePivot,
  User,
} from "types/models";
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
    <div className="container ">
      <div className="mx-auto text-center">
        <h1>Jelenléti Ív - {event?.name}</h1>
        <div>
          <ul className="border">
            {participants?.map((attending, index) => (
              <li key={index}>
                {attending.name} -{" "}
                <Form.Check
                  defaultChecked={attending.pivot.is_present}
                  onClick={toggle(attending)}
                  disabled={isLoading || isParticipantsFetching}
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
