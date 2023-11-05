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
    const updatedDate = new Date(attendance.updated_at);
    return (
        <div>
            <div>
                <div>Event name</div>
                <div>{updated_at.}</div>
            </div>
            <div className="overflow-auto">
                {attendance.memberattendances.foreach((member) => (
                    <span className={`${member.isPresent ? "" : ""}`}></span>
                ))}
                div
            </div>
        </div>
    );
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
