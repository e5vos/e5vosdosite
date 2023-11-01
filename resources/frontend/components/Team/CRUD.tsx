import { useCallback } from "react";
import { useNavigate } from "react-router-dom";

import { CRUDInterface } from "types/misc";
import { Team } from "types/models";

import teamAPI from "lib/api/teamAPI";
import Locale from "lib/locale";

import TeamForm from "components/Forms/TeamForm";

const locale = Locale({
    hu: {
        create: "Csapat létrehozása",
        edit: "Csapat szerkesztése",
    },
    en: {
        create: "Create team",
        edit: "Edit team",
    },
});

export type TeamFormValues = {
    name: string;
    code: string;
    description: string;
};

const TeamCreator = () => {
    const [createTeam] = teamAPI.useCreateTeamMutation();
    const navigate = useNavigate();
    const onSubmit = useCallback(
        async (team: TeamFormValues) => {
            await createTeam(team);
            navigate(`/teams/${team.code}`);
        },
        [createTeam, navigate],
    );
    return (
        <TeamForm
            initialValues={{ code: "", description: "", name: "" }}
            onSubmit={onSubmit}
            submitLabel={locale.create}
        />
    );
};

const TeamUpdater = ({ value: team }: { value: TeamFormValues }) => {
    const [updateTeam] = teamAPI.useEditTeamMutation();
    const navigate = useNavigate();
    const onSubmit = useCallback(
        async (team: TeamFormValues) => {
            await updateTeam(team);
            navigate(`/teams/${team.code}`);
        },
        [updateTeam, navigate],
    );
    return (
        <TeamForm
            initialValues={team}
            onSubmit={onSubmit}
            submitLabel={locale.edit}
            enableReinitialize={true}
        />
    );
};
const TeamCRUD: CRUDInterface<Team> = {
    Creator: TeamCreator,
    Updater: TeamUpdater,
};
export default TeamCRUD;
