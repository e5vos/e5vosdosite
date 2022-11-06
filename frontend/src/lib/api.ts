import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import Cookies from "js-cookie";
import { Result } from "postcss";
import {
  Attendance,
  Presentation,
  Event,
  TeamActivity,
  Team,
  TeamMembership,
  User,
  isUserAttendance,
  isUserAttendancePivot,
  Slot,
} from "types/models";
import { isArray } from "util";
import refreshCSRF from "./csrf";
import routeSwitcher from "./route";
import { RootState } from "./store";

export const api = createApi({
  reducerPath: "e5nApi",
  baseQuery: fetchBaseQuery({
    baseUrl: import.meta.env.VITE_BACKEND,
    prepareHeaders: async (headers, { getState }) => {
      const state = getState() as RootState;

      const token = state.auth.token;
      if (token) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      const xsrfToken = Cookies.get("XSRF-TOKEN");
      if (xsrfToken) {
        headers.set("X-XSRF-TOKEN", xsrfToken);
      }
      return headers;
    },
    credentials: "include",
    mode: "cors",
  }),
  tagTypes: [
    "Event",
    "Presentation",
    "Attendance",
    "TeamActivity",
    "User",
    "EventParticipants",
    "Slot",
  ],
  endpoints: (builder) => ({
    getEvents: builder.query<Event[], number | void>({
      query: (slot?) =>
        slot
          ? routeSwitcher("events.slot", { slot_id: slot })
          : routeSwitcher("events.index"),
      transformResponse: (response: any) => {
        if (!Array.isArray(response)) return [response];
        else return response;
      },
      providesTags: (result) => {
        if (result) {
          return [
            ...result.map(({ id }) => ({ type: "Event", id: id } as const)),
            ...result.map(
              ({ slot }) => ({ type: "Event", id: `LIST${slot}` } as const)
            ),
            { type: "Event", id: "LIST" },
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
    getEventParticipants: builder.query<Array<Attendance>, string>({
      query: (id) => routeSwitcher("event.participants", id),
      providesTags: (result, error, id) => [{ type: "EventParticipants", id }],
    }),
    getSlots: builder.query<Array<Slot>, void>({
      query: () => routeSwitcher("slot.index"),
      transformResponse: (response: any) => {
        if (!Array.isArray(response)) return [response];
        else return response;
      },
      providesTags: (result) => {
        if (result) {
          return [
            ...result.map(({ id }) => ({ type: "Slot", id: id } as const)),
            { type: "Slot", id: "LIST" },
          ];
        } else return [{ type: "Slot", id: "LIST" }];
      },
    }),
    toggleAttendance: builder.mutation<Attendance, Pick<Attendance, "pivot">>({
      query: (data) => {
        const params = isUserAttendancePivot(data.pivot)
          ? { user_id: data.pivot.user_id }
          : { team_code: data.pivot.team_code };
        return {
          url: routeSwitcher("event.attend", {
            eventId: data.pivot.event_id,
          }),
          method: "POST",
          params: params,
        };
      },
    }),
    createEvent: builder.mutation<Event, Event>({
      query: (event) => ({
        url: routeSwitcher("events"),
        method: "POST",
        params: event,
      }),
      invalidatesTags: (result) => [
        { type: "Event", id: `LIST${result?.slot.id}` },
        { type: "Event", id: "LIST" },
      ],
    }),
    getUsersPresentations: builder.query<Presentation[], void>({
      query: () => routeSwitcher("events.mypresentations"),
      providesTags: (result) =>
        result
          ? [
              ...result.map(({ id }) => ({ type: "Event", id } as const)),
              { type: "Event", id: "MYLIST" },
            ]
          : [{ type: "Event", id: "MYLIST" }],
    }),
    signUp: builder.mutation<
      Attendance,
      { attender: string; event: Pick<Event, "id"> }
    >({
      query: (body) => ({
        url: routeSwitcher("event.signup", { id: body.event.id }),
        method: "POST",
        params: { attender: body.attender },
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
        params: data,
      }),
    }),
    promote: builder.mutation<TeamMembership, { promote: boolean }>({
      query: (data) => ({
        url: data.promote
          ? routeSwitcher("team.promote")
          : routeSwitcher("team.demote"),
        method: "POST",
        params: data,
      }),
    }),
    getUserData: builder.query<User, void>({
      query: () => routeSwitcher("user"),
      providesTags: (result) => [{ type: "User", id: result?.id }],
    }),
    setStudentCode: builder.mutation<User, string>({
      query: (code) => ({
        url: routeSwitcher("user.e5code"),
        method: "PATCH",
        params: { e5code: code },
      }),
    }),
  }),
});
