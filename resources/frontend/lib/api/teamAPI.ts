import { RequiredFields } from "types/misc";
import { Team, TeamMembership } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const teamAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getAllTeams: builder.query<
            Omit<Team, "attendance" | "members">[],
            void
        >({
            query: () => routeSwitcher("teams.index"),
        }),
        getMyTeams: builder.query<
            RequiredFields<Team, "attendance" | "members">[],
            void
        >({
            query: (user) => routeSwitcher("user.myteams"), // roland todo
        }),
        getTeam: builder.query<
            RequiredFields<Team, "attendance" | "members">,
            Pick<Team, "code">
        >({
            query: ({ code }) => routeSwitcher("team.show", { teamCode: code }),
        }),
        createTeam: builder.mutation<
            Team,
            Pick<Team, "name" | "code" | "description">
        >({
            query: (data) => ({
                url: routeSwitcher("team.store"),
                method: "POST",
                params: data,
            }),
        }),
        editTeam: builder.mutation<
            Team,
            Pick<Team, "name" | "code" | "description">
        >({
            query: (data) => ({
                url: routeSwitcher("team.edit", { teamCode: data.code }),
                method: "PUT",
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
