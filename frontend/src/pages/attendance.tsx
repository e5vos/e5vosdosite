import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";
import { api } from "lib/api";
import { useParams } from "react-router-dom";
import { isUser, User } from "types/models";
const AttendancePage = () => {
  const { eventid } = useParams<{ eventid: string }>();
  const { data: event, isFetching: isEventFetching } = api.useGetEventQuery(
    eventid ?? ""
  );
  const { data: participants, isFetching: isParticipantsFetching } =
    api.useGetEventParticipantsQuery(eventid ?? "");

  if (!eventid) return <>Error</>;
  if (isEventFetching || isParticipantsFetching) return <Loader />;
  return (
    <div className="container mx-auto ">
      <h1>Jelenléti Ív - {event?.name}</h1>
      <div>
        <ul>
          {participants?.map((attending) => (
            <li>
              {isUser(attending)
                ? `${attending.first_name} ${attending.last_name}`
                : attending.name}
              <Form.Check checked={attendance.present} />
            </li>
          ))}
        </ul>
      </div>
      <div className="flex flex-row">
        <Form.Group>
          <Form.Label>Rossz előadáson vett részt</Form.Label>
          <Form.Control type="text" />
        </Form.Group>
        <Button type="submit">OK</Button>
      </div>
    </div>
  );
};
export default AttendancePage;
