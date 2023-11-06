import { useCallback, useMemo, useState } from "react";

import { RequiredFields } from "types/misc";
import {
    Team,
    TeamMember,
    TeamMemberRole,
    TeamMemberRoleType,
    User,
    UserStub,
} from "types/models";

import teamAPI from "lib/api/teamAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import AttenderShowcase from "components/AttendanceShowcase";
import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Card from "components/UIKit/Card";
import Loader from "components/UIKit/Loader";
import UserSearchCombobox from "components/User/UserSearch";

const cardLocale = Locale({
    hu: {
        membership: (role: TeamMemberRoleType): string => {
            switch (role) {
                case TeamMemberRole.leader:
                    return "Kapitány";
                case TeamMemberRole.member:
                    return "Tag";
                case TeamMemberRole.invited:
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
        resign: "Lemondás",
        leave: "Kilépés",
        noAttendanceYet: "Még nincs aktivitás",
    },
    en: {
        membership: (role: TeamMemberRoleType) => {
            switch (role) {
                case TeamMemberRole.leader:
                    return "Captain";
                case TeamMemberRole.member:
                    return "Member";
                case TeamMemberRole.invited:
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
        resign: "Resign",
        leave: "Leave",
        noAttendanceYet: "No activity yet",
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

    const [invite] = teamAPI.useInviteMutation();
    const [selectedUser, setSelectedUser] = useState<UserStub | null>(null);

    const currentUsersMembership = useMemo(
        () => team.members?.find((member) => member.id === user?.id),
        [team, user],
    );

    const inviteSelected = useCallback(async () => {
        if (!selectedUser) return;
        await invite({
            user_id: selectedUser.id,
            team_code: team.code,
        });
    }, [invite, selectedUser, team.code]);

    const moreThanOneLeader = useMemo(
        () =>
            team.members.filter((e) => e.pivot.role === TeamMemberRole.leader)
                .length > 1,
        [team],
    );

    if (!team || !user) return <Loader />;

    const canPromote = (member: TeamMember) =>
        member.pivot.role === TeamMemberRole.member &&
        currentUsersMembership?.pivot.role === TeamMemberRole.leader;
    const canDemote = (member: TeamMember) =>
        (member.id === user.id &&
            (member.pivot.role !== TeamMemberRole.leader ||
                moreThanOneLeader)) ||
        (member.pivot.role !== TeamMemberRole.leader &&
            currentUsersMembership?.pivot.role === TeamMemberRole.leader);

    return (
        <Card
            title={team.name}
            subtitle={team.code}
            className="mx-auto max-w-4xl"
            titleClassName="text-2xl"
        >
            {team.description}
            {currentUsersMembership?.pivot.role === TeamMemberRole.invited && (
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
                                    promote: true,
                                })
                            }
                        >
                            {cardLocale.invite.accept}
                        </Button>
                        <Button
                            variant="danger"
                            onClick={() =>
                                promote({
                                    user_id: user.id,
                                    team_code: team.code,
                                    promote: false,
                                })
                            }
                        >
                            {cardLocale.invite.decline}
                        </Button>
                    </ButtonGroup>
                </div>
            )}
            <hr />
            {isAdmin(user) && (
                <span className="hidden md:block">
                    <h2 className="text-center text-lg font-bold">
                        {cardLocale.activities}
                    </h2>
                    <div>
                        {team.activity?.map((e) => (
                            <AttenderShowcase key={e.pivot.id} attender={e} />
                        )) ?? (
                            <div className="text-center italic">
                                {cardLocale.noAttendanceYet}
                            </div>
                        )}
                    </div>
                </span>
            )}
            <hr />
            <h2 className="text-center text-lg font-bold">
                {cardLocale.members}
            </h2>
            <div>
                {team.members?.map((member) => (
                    <div
                        key={member.id}
                        className="mb-4 border-8 border-gray-300 px-3 sm:mb-4 sm:grid sm:grid-cols-2 sm:border-none sm:px-0"
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
                                                promote: true,
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
                                            promote({
                                                user_id: member.id,
                                                team_code: team.code,
                                                promote: false,
                                            })
                                        }
                                    >
                                        {member.id === user.id
                                            ? member.pivot.role ===
                                              TeamMemberRole.member
                                                ? cardLocale.resign
                                                : cardLocale.leave
                                            : cardLocale.demote}
                                    </Button>
                                )}
                            </ButtonGroup>
                        )}
                    </div>
                ))}
            </div>
            {currentUsersMembership?.pivot.role === TeamMemberRole.leader && (
                <div className="mt-6 grid-cols-3 gap-6 md:grid">
                    <UserSearchCombobox onChange={setSelectedUser} />
                    <Button
                        className="col-span-1 mt-3 md:mt-0"
                        onClick={inviteSelected}
                    >
                        {cardLocale.invite.send}
                    </Button>
                </div>
            )}
        </Card>
    );
};

export default TeamCard;
