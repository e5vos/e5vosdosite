import { RequiredFields } from "types/misc";
import { Team, TeamMember, TeamMemberRole, User } from "types/models";

import teamAPI from "lib/api/teamAPI";
import Locale from "lib/locale";

import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Card from "components/UIKit/Card";
import Loader from "components/UIKit/Loader";
import UserSearchCombobox from "components/User/UserSearch";

const cardLocale = Locale({
    hu: {
        membership: (role: TeamMemberRole): string => {
            switch (role) {
                case "vezető":
                    return "Kapitány";
                case "tag":
                    return "Tag";
                case "meghívott":
                    return "Meghívott";
                default:
                    return "Ismeretlen";
            }
        },
        invite: {
            pending: "Függőben lévő meghívó",
            accept: "Meghívó elfogadása",
            decline: "Meghívó elutasítása",
            send: "Meghívó küldése",
        },
        activities: "Aktivitás",
        members: "Tagok",
        promote: "Előléptetés",
        demote: "Kizárás",
        noActivityYet: "Még nincs aktivitás",
    },
    en: {
        membership: (role: TeamMemberRole) => {
            switch (role) {
                case "vezető":
                    return "Captain";
                case "tag":
                    return "Member";
                case "meghívott":
                    return "Invited";
                default:
                    return "Unknown";
            }
        },
        invite: {
            pending: "Pending invite",
            accept: "Accept invite",
            decline: "Decline invite",
            send: "Send invite",
        },
        activities: "Activities",
        members: "Members",
        promote: "Promote",
        demote: "Demote",
        noActivityYet: "No activity yet",
    },
});

const TeamCard = ({
    team,
    currentUser: user,
}: {
    team: RequiredFields<Team, "activity" | "members">;
    currentUser: User;
}) => {
    const [promote] = teamAPI.usePromoteMutation();
    const [demote] = teamAPI.useDemoteMutation();
    if (!team || !user) return <Loader />;
    const currentUsersMembership = team.members?.find(
        (member) => member.id === user?.id,
    );
    const canPromote = (member: TeamMember) =>
        true ||
        (currentUsersMembership?.pivot.role === "vezető" &&
            member.pivot.role === "tag");
    const canDemote = (member: TeamMember) =>
        true ||
        (currentUsersMembership?.pivot.role === "vezető" &&
            member.pivot.role !== "vezető");
    return (
        <Card
            title={team.name}
            subtitle={team.code}
            className="mx-auto max-w-4xl"
            titleClassName="text-2xl"
        >
            {team.description}
            {currentUsersMembership?.pivot.role === "meghívott" ||
                (true && (
                    <div className="mb-3">
                        <h3 className="text-center text-xl font-bold text-yellow-500">
                            {cardLocale.invite.pending}
                        </h3>
                        <ButtonGroup className="w-full">
                            <Button
                                variant="success"
                                onClick={() =>
                                    promote({
                                        user_id: user.id,
                                        team_code: team.code,
                                    })
                                }
                            >
                                {cardLocale.invite.accept}
                            </Button>
                            <Button
                                variant="danger"
                                onClick={() =>
                                    demote({
                                        user_id: user.id,
                                        team_code: team.code,
                                    })
                                }
                            >
                                {cardLocale.invite.decline}
                            </Button>
                        </ButtonGroup>
                    </div>
                ))}
            <hr />
            <h2 className="text-center text-lg font-bold">
                {cardLocale.activities}
            </h2>
            <div>
                {team.activity?.map((e) => (
                    <></>
                    //<AttendanceShowcase key={e.attendance.id} attendance={e} />
                )) ?? (
                    <div className="text-center italic">
                        {cardLocale.noActivityYet}
                    </div>
                )}
            </div>
            <hr />
            <h2 className="text-center text-lg font-bold">
                {cardLocale.members}
            </h2>
            <div>
                {team.members?.map((member) => (
                    <div
                        key={member.id}
                        className=" border-8 border-gray-300 px-3 sm:grid sm:grid-cols-2 sm:border-none sm:px-0"
                    >
                        <div className="py-2 text-center sm:mr-5 sm:text-left">
                            <span className="font-semibold">
                                {member.name} ({member.ejg_class})
                            </span>{" "}
                            -{" "}
                            <span className="italic">
                                {cardLocale.membership(member.pivot.role)}
                            </span>
                        </div>
                        {(canPromote(member) || canDemote(member)) && (
                            <ButtonGroup className="my-1">
                                {canPromote(member) && (
                                    <Button
                                        variant="success"
                                        onClick={() =>
                                            promote({
                                                user_id: member.id,
                                                team_code: team.code,
                                            })
                                        }
                                    >
                                        {cardLocale.promote}
                                    </Button>
                                )}
                                {canDemote(member) && (
                                    <Button
                                        variant="danger"
                                        onClick={() =>
                                            demote({
                                                user_id: member.id,
                                                team_code: team.code,
                                            })
                                        }
                                    >
                                        {cardLocale.demote}
                                    </Button>
                                )}
                            </ButtonGroup>
                        )}
                    </div>
                ))}
            </div>
            <div className="mt-6 grid grid-cols-3 gap-6">
                <UserSearchCombobox />
                <Button className="col-span-1">{cardLocale.invite.send}</Button>
            </div>
        </Card>
    );
};

export default TeamCard;
