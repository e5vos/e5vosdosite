import { RequiredAndOmitFields, RequiredFields } from 'types/misc'
import { Team, TeamMembership } from 'types/models'

import routeSwitcher from 'lib/route'

import baseAPI from '.'

export const teamAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getAllTeams: builder.query<Omit<Team, 'activity' | 'members'>[], void>({
            query: () => routeSwitcher('teams.index'),
            providesTags: () => [{ type: 'Team', id: 'LIST' }],
        }),
        getMyTeams: builder.query<
            RequiredFields<Team, 'activity' | 'members'>[],
            void
        >({
            query: () => routeSwitcher('user.myteams'),
            providesTags: () => [{ type: 'Team', id: 'MYTEAMS' }],
        }),
        getTeam: builder.query<
            RequiredFields<Team, 'activity' | 'members'>,
            Pick<Team, 'code'>
        >({
            query: ({ code }) => routeSwitcher('team.show', { teamCode: code }),
            providesTags: (result) =>
                result ? [{ type: 'Team', id: result?.code }] : [],
        }),
        createTeam: builder.mutation<
            Omit<Team, 'activity' | 'members'>,
            Pick<Team, 'name' | 'code' | 'description'>
        >({
            query: (data) => ({
                url: routeSwitcher('team.store'),
                method: 'POST',
                body: data,
            }),
            invalidatesTags: [
                { type: 'Team', id: 'LIST' },
                { type: 'Team', id: 'MYTEAMS' },
            ],
        }),
        editTeam: builder.mutation<
            Omit<Team, 'activity' | 'members'>,
            Pick<Team, 'name' | 'code' | 'description'>
        >({
            query: (data) => ({
                url: routeSwitcher('team.edit', { teamCode: data.code }),
                method: 'PUT',
                body: data,
            }),
            invalidatesTags: (res, err, arg) =>
                err
                    ? []
                    : [
                          { type: 'Team', id: arg.code },
                          { type: 'Team', id: 'LIST' },
                          { type: 'Team', id: 'MYTEAMS' },
                          { type: 'User', id: 'CURRENT' },
                      ],
        }),
        promote: builder.mutation<
            RequiredAndOmitFields<Team, 'members', 'activity'>,
            Pick<TeamMembership, 'user_id' | 'team_code'> & { promote: boolean }
        >({
            query: (data) => ({
                url: routeSwitcher('team.promote', {
                    teamCode: data.team_code,
                }),
                method: 'PUT',
                body: {
                    userId: data.user_id,
                    promote: data.promote,
                },
            }),
            invalidatesTags: (res, err, arg) =>
                err
                    ? []
                    : [
                          { type: 'Team', id: arg.team_code },
                          { type: 'Team', id: 'LIST' },
                          { type: 'Team', id: 'MYTEAMS' },
                          { type: 'User', id: arg.user_id },
                          { type: 'User', id: 'CURRENT' },
                          { type: 'User', id: 'LIST' },
                      ],
        }),
    }),
})

export default teamAPI
