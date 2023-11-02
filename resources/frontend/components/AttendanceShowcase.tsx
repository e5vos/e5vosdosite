import {
    Attendance,
    TeamAttendance,
    UserAttendance,
    isTeamAttendance,
} from "types/models";

const TeamAttendanceShowcase = ({
    attendance,
}: {
    attendance: TeamAttendance;
}) => {
    return <>This is a team attendance</>;
};

const UserAttendanceShowcase = ({
    attendance,
}: {
    attendance: UserAttendance;
}) => {
    return <>This is a user attendance</>;
};

const AttendanceShowcase = ({
    attendance,
    ...rest
}: {
    attendance: Attendance;
}) => {
    if (isTeamAttendance(attendance)) {
        return <TeamAttendanceShowcase attendance={attendance} {...rest} />;
    } else {
        return <UserAttendanceShowcase attendance={attendance} {...rest} />;
    }
};

export default AttendanceShowcase;
