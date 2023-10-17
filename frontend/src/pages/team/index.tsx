import useUser from "hooks/useUser";
import React, { useState } from "react";
import QRCode from "react-qr-code";
import { useNavigate } from "react-router-dom";

import { Team } from "types/models";

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
  },
  en: {
    your_teams: "Your teams",
    view_team: "View team",
    view_code: "View team code",
    leave_team: "Leave team",
    team_code: (team_name: string) => `The code of the ${team_name} team is:`,
  },
});

const testTeams: { data: Team[] } = {
  data: [
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
    {
      code: "Test",
      description: "asd",
      name: "test",
      members: [],
    },
  ],
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
        open={shownTeam !== null}
        onClose={() => setShownTeam(null)}
      >
        <QRCode value={shownTeam?.code ?? ""} />
      </Dialog>
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
                    onClick={() => navigate(`/csapat/${team.code}`)}
                  >
                    {locale.view_team}
                  </Button>
                  <Button variant="info" onClick={() => setShownTeam(team)}>
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
