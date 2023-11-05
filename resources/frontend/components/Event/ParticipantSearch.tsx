import { RequiredFields } from "types/misc";
import { Attendance, Event, isTeamAttendance } from "types/models";

import Form from "components/UIKit/Form";

const displayName = (e: Attendance) => {
    if (isTeamAttendance(e)) {
        return e.name;
    } else {
        return `${e.name} - ${e.ejg_class}}`;
    }
};

const ParticipantSearch = ({
    event,
    onChange,
}: {
    event: RequiredFields<Event, "attendees">;
    onChange: (value: Attendance) => any;
}) => {
    return (
        <Form.ComboBox
            options={event.attendees}
            getElementName={(e) => e.name}
            renderElement={(e) => <span>{displayName(e)}</span>}
            filter={(s) => (e) =>
                displayName(e)
                    .toLocaleLowerCase()
                    .startsWith(s.toLocaleLowerCase())
            }
            onChange={(e) => {
                if (!e) return;
                onChange(e);
            }}
        />
    );
};

export default ParticipantSearch;
