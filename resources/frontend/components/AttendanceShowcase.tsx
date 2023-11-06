import {
    Attender,
    AttendingTeam,
    AttendingUser,
    isAttenderTeam,
} from "types/models";

const TeamAttenderShowcase = ({
    attender: attendance,
}: {
    attender: AttendingTeam;
}) => {
    return (
        <div>
            <div>
                <div>Event name</div>
            </div>
            <div className="overflow-auto">div</div>
        </div>
    );
};

const UserAttenderShowcase = ({
    attender: attendance,
}: {
    attender: AttendingUser;
}) => {
    return <>This is a user attendance</>;
};

const AttenderShowcase = ({ attender, ...rest }: { attender: Attender }) => {
    if (isAttenderTeam(attender)) {
        return <TeamAttenderShowcase attender={attender} {...rest} />;
    } else {
        return <UserAttenderShowcase attender={attender} {...rest} />;
    }
};

export default AttenderShowcase;
