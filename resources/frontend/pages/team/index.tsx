import useUser from "hooks/useUser";
import React, { useState } from "react";
import QRCode from "react-qr-code";
import { useNavigate } from "react-router-dom";

import { Team, TeamMemberRole, TeamMembership } from "types/models";

import teamAPI from "lib/api/teamAPI";
import Locale from "lib/locale";

import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import Card from "components/UIKit/Card";
import Dialog from "components/UIKit/Dialog";
import Loader from "components/UIKit/Loader";
import { Title } from "components/UIKit/Typography";

const locale = Locale({
    hu: {
        your_teams: "Csapataid",
        view_team: "Csapat megtekintése",
        view_code: "Csapat kódjának megtekintése",
        leave_team: "Csapat elhagyása",
        team_code: (team_name: string) => `A(z) ${team_name} csapat kódja:`,
        membership: (role: TeamMemberRole): string => {
            switch (role) {
                case "captain":
                    return "Kapitány";
                case "member":
                    return "Tag";
                case "invited":
                    return "Meghívott";
                default:
                    return "Ismeretlen";
            }
        },
    },
    en: {
        your_teams: "Your teams",
        view_team: "View team",
        view_code: "View team code",
        leave_team: "Leave team",
        team_code: (team_name: string) =>
            `The code of the ${team_name} team is:`,
        membership: (role: TeamMemberRole) => {
            switch (role) {
                case "captain":
                    return "Captain";
                case "member":
                    return "Member";
                case "invited":
                    return "Invited";
                default:
                    return "Unknown";
            }
        },
    },
});

const testTeams: { data: Team[] } = {
    data: [
        {
            code: "Test",
            description: "asd",
            name: "test",
            members: [
                {
                    role: "captain",
                    user: {
                        id: 5,
                        e5code: "",
                        name: "Béluska",
                    },
                },
                {
                    role: "member",
                    user: {
                        id: 5,
                        e5code: "",
                        name: "Krisztike",
                    },
                },
                {
                    role: "invited",
                    user: {
                        id: 6,
                        e5code: "",
                        name: "Gizike",
                    },
                },
            ],
        },
        {
            code: "Test",
            description: "asd",
            name: "test",
            members: [],
        },
        {
            code: "Test",
            description: "asd",
            name: "test",
            members: [],
        },
        {
            code: "Test",
            description: "asd",
            name: "test",
            members: [],
        },
    ],
};

const Test = ({ shownTeam }: { shownTeam: Team }) => {
    const { user } = useUser();

    const currentUsersMembership: TeamMembership | undefined = {
        role: "captain",
    };

    const [promote] = teamAPI.usePromoteMutation();
    const [demote] = teamAPI.useDemoteMutation();

    return (
        <Card
            title={shownTeam.name}
            subtitle={shownTeam.code}
            className="mx-auto max-w-4xl"
            titleClassName="text-2xl"
        >
            {shownTeam.description}
            {currentUsersMembership?.role === "invited" && (
                <ButtonGroup className="w-full">
                    <Button
                        variant="success"
                        onClick={() => promote({ user: user, team: shownTeam })}
                    >
                        Accept invite
                    </Button>
                    <Button
                        variant="danger"
                        onClick={() => demote({ user: user, team: shownTeam })}
                    >
                        Decline invite
                    </Button>
                </ButtonGroup>
            )}
            <hr />
            <h2 className="text-center text-lg font-bold">Activties</h2>

            <hr />
            <h2 className="text-center text-lg font-bold">Members</h2>
            {shownTeam.members?.map((member) => {
                return (
                    <div className="my-3 border-8 border-gray-300 px-3 sm:grid sm:grid-cols-2 sm:border-none sm:px-0">
                        <div className="my-2 py-3 text-center sm:mr-5 sm:text-left">
                            <span className="font-semibold">
                                {member.user!.name}
                            </span>{" "}
                            -{" "}
                            <span className="italic">
                                {locale.membership(member.role)}
                            </span>
                        </div>
                        <ButtonGroup>
                            {currentUsersMembership?.role === "captain" &&
                                member.role === "member" && (
                                    <Button
                                        variant="success"
                                        onClick={() =>
                                            promote({
                                                user: member.user!,
                                                team: shownTeam,
                                            })
                                        }
                                    >
                                        Promote
                                    </Button>
                                )}
                            {currentUsersMembership?.role === "captain" &&
                                member.role !== "captain" && (
                                    <Button
                                        variant="danger"
                                        onClick={() =>
                                            demote({
                                                user: member.user!,
                                                team: shownTeam,
                                            })
                                        }
                                    >
                                        Kick
                                    </Button>
                                )}
                        </ButtonGroup>
                    </div>
                );
            })}
        </Card>
    );
};

const YourTeamsPage = () => {
    const [shownTeam, setShownTeam] = useState<Team | null>(null);
    const { user } = useUser();
    const navigate = useNavigate();
    const { data: teams } = testTeams; //teamAPI.useGetTeamsQuery(user ?? { id: -1 });
    const [leave] = teamAPI.useLeaveMutation();
    console.log(user);
    if (!user) return <Loader />;
    return (
        <>
            <Dialog
                title={locale.team_code(shownTeam?.name ?? "")}
                open={shownTeam !== null && false}
                onClose={() => setShownTeam(null)}
            >
                {shownTeam && <QRCode value={shownTeam?.code} />}
            </Dialog>
            {shownTeam && <Test shownTeam={shownTeam} />}
            <div>
                <Title>{locale.your_teams}</Title>
                <div className="gap-2 md:grid  md:grid-cols-3 xl:grid-cols-4">
                    {teams?.map((team) => (
                        <Card
                            key={team.code}
                            title={team.name}
                            subtitle={team.code}
                            buttonBar={
                                <ButtonGroup>
                                    <Button
                                        variant="primary"
                                        onClick={() =>
                                            navigate(`/csapat/${team.code}`)
                                        }
                                    >
                                        {locale.view_team}
                                    </Button>
                                    <Button
                                        variant="info"
                                        onClick={() => setShownTeam(team)}
                                    >
                                        {locale.view_code}
                                    </Button>
                                    <Button
                                        variant="danger"
                                        onClick={() =>
                                            leave({
                                                user: user,
                                                team: team,
                                            })
                                        }
                                    >
                                        {locale.leave_team}
                                    </Button>
                                </ButtonGroup>
                            }
                        >
                            {team.description}
                        </Card>
                    ))}
                </div>
            </div>
        </>
    );
};
export default YourTeamsPage;
