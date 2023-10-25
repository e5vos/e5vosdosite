import { Team, TeamMembership, User } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const teamAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getTeams: builder.query<Team[], Pick<User, "id">>({
            query: (user) => routeSwitcher("teams", { userid: user.id }),
        }),
        getAllTeams: builder.query<Team[], void>({
            query: () => routeSwitcher("teams"),
        }),
        getTeam: builder.query<Team, string>({
            query: (teamcode) => routeSwitcher("team", { team: teamcode }),
        }),
        createTeam: builder.mutation<Team, Pick<Team, "name" | "description">>({
            query: (data) => ({
                url: routeSwitcher("team.create"),
                method: "POST",
                params: data,
            }),
        }),
        promote: builder.mutation<void, Pick<TeamMembership, "user" | "team">>({
            query: (data) => ({
                url: routeSwitcher("team.promote", {
                    user_id: data.team?.code ?? -1,
                    team_code: data.user?.id ?? -1,
                }),
                method: "POST",
                params: data,
            }),
        }),

        demote: builder.mutation<void, Pick<TeamMembership, "user" | "team">>({
            query: (data) => ({
                url: routeSwitcher("team.demote", {
                    user_id: data.team?.code ?? -1,
                    team_code: data.user?.id ?? -1,
                }),
                method: "POST",
                params: data,
            }),
        }),
        leave: builder.mutation<void, Pick<TeamMembership, "user" | "team">>({
            query: (data) => ({
                url: routeSwitcher("team.leave", {
                    user_id: data.team?.code ?? -1,
                    team_code: data.user?.id ?? -1,
                }),
                method: "DELETE",
                params: data,
            }),
        }),
    }),
});

export default teamAPI;
