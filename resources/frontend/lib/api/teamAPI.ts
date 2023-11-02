import { RequiredFields } from "types/misc";
import { Team, TeamMembership } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const teamAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getAllTeams: builder.query<Omit<Team, "activity" | "members">[], void>({
            query: () => routeSwitcher("teams.index"),
        }),
        getTeam: builder.query<
            RequiredFields<Team, "attendance">,
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
        invite: builder.mutation<
            void,
            Pick<TeamMembership, "user_id" | "team_code">
        >({
            query: (data) => ({
                url: routeSwitcher("team.invite", {
                    team_code: data.team_code ?? -1,
                }),
                method: "POST",
                params: {
                    id: data.user_id,
                },
            }),
        }),
        promote: builder.mutation<
            void,
            Pick<TeamMembership, "user_id" | "team_code">
        >({
            query: (data) => ({
                url: routeSwitcher("team.promote", {
                    user_id: data.team_code ?? -1,
                    team_code: data.user_id ?? -1,
                }),
                method: "POST",
                params: data,
            }),
        }),

        demote: builder.mutation<
            void,
            Pick<TeamMembership, "user_id" | "team_code">
        >({
            query: (data) => ({
                url: routeSwitcher("team.demote", {
                    user_id: data.team_code ?? -1,
                    team_code: data.user_id ?? -1,
                }),
                method: "POST",
                params: data,
            }),
        }),
        leave: builder.mutation<
            void,
            Pick<TeamMembership, "user_id" | "team_code">
        >({
            query: (data) => ({
                url: routeSwitcher("team.leave", {
                    user_id: data.team_code ?? -1,
                    team_code: data.user_id ?? -1,
                }),
                method: "DELETE",
                params: data,
            }),
        }),
        attendMember: builder.mutation<
            void,
            { user_id: number; attendance_id: number; is_present: boolean }
        >({
            query: (data) => ({
                url: routeSwitcher("team.attend", {}),
                method: "POST",
                params: data,
            }),
        }),
    }),
});

export default teamAPI;
