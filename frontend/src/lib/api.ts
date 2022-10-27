import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { Result } from "postcss";
import {
  Attendance,
  Presentation,
  Event,
  TeamActivity,
  Team,
  TeamMembership,
} from "types/models";
import routeSwitcher from "./route";
import { RootState } from "./store";

export const api = createApi({
  reducerPath: "e5nApi",
  baseQuery: fetchBaseQuery({
    baseUrl: import.meta.env.VITE_BACKEND,
    prepareHeaders: (headers, { getState }) => {
      const token = (getState() as RootState).auth.token;
      if (token) {
        headers.set("authorization", `Bearer ${token}`);

      }
      
      return headers;
    },
    credentials: 'include',
    
  }),
  tagTypes: ["Event", "Presentation", "Attendance", "TeamActivity"],
  endpoints: (builder) => ({
    getEvents: builder.query<Event[], number | void>({
      query: (slot?) =>
        slot ? routeSwitcher("events.slot", { slot_id:slot }) : routeSwitcher("events.index"),
      providesTags: (result) => {
        if (result) {
          
          return [
            ...result.map(({ id }) => ({ type: "Event", id: id } as const)),
            ...result.map(({slot})=> ({type: "Event", id:`LIST${slot}`} as const)),
            { type: "Event", id: "LIST" }
          ];
        } else {
          return [{ type: "Event", id: "LIST" }];
        }
      },
      onCacheEntryAdded: async (
        arg,
        { cacheDataLoaded, updateCachedData, cacheEntryRemoved }
      ) => {
        try {
          await cacheDataLoaded;

          const listener = (msg: MessageEvent) => {
            const data = msg.data;

            updateCachedData((draft) => {
              draft.push(data);
            });
          };

          // echo.addEventListener('message',listener)
        } catch (e) {
          // No-op if removed before loaded, thus cacheDataLoaded throws
        }
        await cacheEntryRemoved;
        // echo.close()
      },
    }),
    getEvent: builder.query<Event, string>({
      query: (id) => routeSwitcher("event.show", id),
      providesTags: (result, error, id) => [{ type: "Event", id }],
    }),
    createEvent: builder.mutation<Event, Event>({
      query: (event) => ({
        url: routeSwitcher("events"),
        method: "POST",
        body: event,
      }),
      invalidatesTags: (result) => [{type: "Event", id: `LIST${result?.slot.id}`},{ type: "Event", id: "LIST" }],
    }),
    getUsersPresentations: builder.query<Presentation[], void>({
      query: () => routeSwitcher("user.presentations"),
      providesTags: (result) =>
        result
          ? [
              ...result.map(({ id }) => ({ type: "Event", id } as const)),
              { type: "Event", id: "MYLIST" },
            ]
          : [{ type: "Event", id: "MYLIST" }],
    }),
    signUp: builder.mutation<Attendance, Pick<Event, "id">>({
      query: (body) => ({
        url: routeSwitcher("event.signup"),
        method: "POST",
        body: body,
      }),
      onQueryStarted: async (arg) => {
        /** TODO: OPTIMISTIC UPDATE */
      },
    }),
    getTeams: builder.query<Team[], void>({
      query: () => routeSwitcher("teams"),
    }),
    getTeam: builder.query<Team, string>({
      query: (teamcode) => routeSwitcher("team", { team: teamcode }),
    }),
    getTeamActivity: builder.query<TeamActivity[], string>({
      query: (teamcode) => routeSwitcher("team.activity", { team: teamcode }),
    }),
    createTeam: builder.mutation<Team, Pick<Team, "name" | "description">>({
      query: (data) => ({
        url: routeSwitcher("team.create"),
        method: "POST",
        body: data,
      }),
    }),
    promote: builder.mutation<TeamMembership, { promote: boolean }>({
      query: (data) => ({
        url: data.promote
          ? routeSwitcher("team.promote")
          : routeSwitcher("team.demote"),
        method: "POST",
        body: data,
      }),
    }),
  }),
});
