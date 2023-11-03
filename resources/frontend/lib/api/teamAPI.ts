import { Team, TeamMembership, User } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const teamAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getMyTeams: builder.query<Team[], void>({
            query: (user) => routeSwitcher("user.myteams"), // roland todo
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
        promote: builder.mutation<
            Team,
            Pick<TeamMembership, "user_id" | "team_code">
        >({
            query: (data) => ({
                url: routeSwitcher("team.promote", {
                    teamCode: data.team_code,
                }),
                method: "POST",
                params: {
                    userId: data.user_id,
                },
            }),
        }),

        demote: builder.mutation<
            Team,
            Pick<TeamMembership, "user_id" | "team_code">
        >({
            query: (data) => ({
                url: routeSwitcher("team.demote", {
                    team_code: data.team_code,
                }),
                method: "POST",
                params: {
                    userId: data.user_id,
                },
            }),
        }),
        invite: builder.mutation<
            Team,
            Pick<TeamMembership, "team_code" | "user_id">
        >({
            query: (data) =>
                routeSwitcher("team.invite", {
                    teamCode: data.team_code,
                }),
        }),
    }),
});

export default teamAPI;
