import { RequiredAndOmitFields, RequiredFields } from "types/misc";
import { Team, TeamMembership } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const teamAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getAllTeams: builder.query<Omit<Team, "activity" | "members">[], void>({
            query: () => routeSwitcher("teams.index"),
        }),
        getMyTeams: builder.query<
            RequiredFields<Team, "activity" | "members">[],
            void
        >({
            query: (user) => routeSwitcher("user.myteams"),
        }),
        getTeam: builder.query<
            RequiredFields<Team, "activity" | "members">,
            Pick<Team, "code">
        >({
            query: ({ code }) => routeSwitcher("team.show", { teamCode: code }),
        }),
        createTeam: builder.mutation<
            Omit<Team, "activity" | "members">,
            Pick<Team, "name" | "code" | "description">
        >({
            query: (data) => ({
                url: routeSwitcher("team.store"),
                method: "POST",
                params: data,
            }),
        }),
        editTeam: builder.mutation<
            Omit<Team, "activity" | "members">,
            Pick<Team, "name" | "code" | "description">
        >({
            query: (data) => ({
                url: routeSwitcher("team.edit", { teamCode: data.code }),
                method: "PUT",
                params: data,
            }),
        }),
        promote: builder.mutation<
            RequiredAndOmitFields<Team, "members", "activity">,
            Pick<TeamMembership, "user_id" | "team_code"> & { promote: boolean }
        >({
            query: (data) => ({
                url: routeSwitcher("team.promote", {
                    teamCode: data.team_code,
                }),
                method: "PUT",
                params: {
                    userId: data.user_id,
                    promote: data.promote,
                },
            }),
        }),
        invite: builder.mutation<
            RequiredAndOmitFields<Team, "members", "activity">,
            Pick<TeamMembership, "team_code" | "user_id">
        >({
            query: (data) => ({
                url: routeSwitcher("team.invite", {
                    teamCode: data.team_code,
                }),
                method: "POST",
                params: {
                    userId: data.user_id,
                },
            }),
        }),
    }),
});

export default teamAPI;
