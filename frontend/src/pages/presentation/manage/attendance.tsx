import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import { Attendance, IndivitualAttendance, User } from "types/models";

const PresentationAttendancePage = () => {
  const signedup: IndivitualAttendance[] = [];
  return (
    <div className="container mx-auto">
      <h1>Jelenléti Ív</h1>
      <div>
        <ul>
          {signedup.map((attendance) => (
            <li>
              {attendance.user.first_name} {attendance.user.last_name} -{" "}
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
export default PresentationAttendancePage;
