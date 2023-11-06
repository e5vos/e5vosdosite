import { useEffect } from "react";

import { Team } from "types/models";

import teamAPI from "lib/api/teamAPI";

import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const TeamSearchCombobox = ({
    onChange,
    initialValue,
    myTeams = true,
}: {
    onChange: (value: Pick<Team, "code" | "name">) => void;
    initialValue: Team;
    myTeams?: boolean;
}) => {
    const [getAllTeams, { data: allteams }] = teamAPI.useLazyGetAllTeamsQuery();
    const [getMyTeams, { data: myteams }] = teamAPI.useLazyGetMyTeamsQuery();

    const teams = myTeams ? myteams : allteams;
    useEffect(() => {
        if (myTeams) getMyTeams();
        else getAllTeams();
    }, [getAllTeams, getMyTeams, myTeams]);

    if (!teams) return <Loader />;
    return (
        <div className="max-w-sm">
            <Form.ComboBox
                options={teams}
                renderElement={(team) => <span>{team.name}</span>}
                getElementName={(team) => team.name}
                onChange={(t) => {
                    if (!t) return;
                    onChange(t);
                }}
            />
        </div>
    );
};

export default TeamSearchCombobox;
