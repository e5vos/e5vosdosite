import {createApi, fetchBaseQuery} from "@reduxjs/toolkit/query/react"
import { Attendance, Presentation, Event, TeamActivity } from "types/models"
import routeSwitcher from "./route"
import { RootState } from "./store"

export const api = createApi({
    reducerPath: "e5nApi",
    baseQuery: fetchBaseQuery({
        baseUrl: "/api",
        prepareHeaders: (headers, {getState}) => {
            const token = (getState() as RootState).auth.token
            if (token) {
                headers.set("authorization", `Bearer ${token}`)
            }
            return headers
        }
    }),
    endpoints: (builder) => ({
        getEvents: builder.query<Event[],void>({
            query: () => routeSwitcher('events')
        }),
        getPersentations: builder.query<Presentation[],number>({
            query: (slot) => routeSwitcher('presentations',{slot:slot})
        }),
        getUsersPresentations: builder.query<Presentation[],void>({
            query: () => routeSwitcher('user.presentations')
        }),
        getTeamActivity: builder.query<TeamActivity[],string>({
            query: (teamcode) => routeSwitcher('team.activity',{team:teamcode})
        }),
        signUp: builder.mutation<Attendance,Pick<Event, "id">>({
            query: (body) => ({
                url: routeSwitcher('event.signup'),
                method: "POST",
                body: body
            }),
            async onQueryStarted(arg){
                /** TODO: OPTIMISTIC UPDATE */
            }
        })
        
    })
})