import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import Cookies from "js-cookie";
import { Result } from "postcss";
import {
  Attendance,
  Presentation,
  Event,
  Team,
  TeamMembership,
  User,
  isUserAttendance,
  Slot,
  Location,
} from "types/models";
import routeSwitcher from "./route";
import { RootState } from "./store";

export const api = createApi({
  reducerPath: "e5nApi",
  baseQuery: fetchBaseQuery({
    fetchFn(input, init?) {
      if (input instanceof Request && input.url.includes("-1")) {
        throw new Error("-1 in URL");
      } else {
        return fetch(input, init);
      }
    },
    baseUrl: import.meta.env.VITE_BACKEND,
    prepareHeaders: async (headers, { getState }) => {
      headers.set("Accept", "application/json");

      const state = getState() as RootState;

      const token = state.auth.token;
      if (token) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      let xsrfToken = Cookies.get("XSRF-TOKEN");
      if (xsrfToken?.at(-1) === "=") xsrfToken = xsrfToken.slice(0, -1);
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
    "Location",
  ],
  endpoints: (builder) => ({
    getEvents: builder.query<Event[] | undefined, number | void>({
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
    }),
    getEvent: builder.query<Event, number>({
      query: (id) => routeSwitcher("event.show", id),
      providesTags: (result, error, id) => [{ type: "Event", id }],
    }),
    getEventParticipants: builder.query<Array<Attendance>, number>({
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
    toggleAttendance: builder.mutation<Attendance, Attendance>({
      query: (data) => {
        const params = {
          attender: isUserAttendance(data) ? data.e5code : data.code,
        };
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
        { type: "Event", id: `LIST${result?.slot_id}` },
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
      { event: Pick<Event, "id">; attender: string | number }
    >({
      query: (body) => ({
        url: routeSwitcher("event.signup", { id: body.event.id }),
        method: "POST",
        params: { attender: body.attender },
      }),
      invalidatesTags: (result) => {
        return [];
        //return [{ type: "Event", id: result?.pivot.event_id } as const];
      },
    }),
    cancelSignUp: builder.mutation<
      void,
      { attender: string; event: Pick<Event, "id"> }
    >({
      query: (body) => ({
        url: routeSwitcher("event.signup", { id: body.event.id }),
        method: "DELETE",
        params: { attender: body.attender },
      }),
    }),
    getTeams: builder.query<Team[], void>({
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
    getFreeUsers: builder.query<User[], number>({
      query: (slot) => routeSwitcher("slot.free_students", { slotId: slot }),
    }),
    clearCache: builder.mutation<void, void>({
      query: () => ({
        url: routeSwitcher("cache.clear"),
        method: "POST",
      }),
    }),
    updateEvent: builder.mutation<Event, Omit<Event, "occupancy">>({
      query: (event) => ({
        url: routeSwitcher("event.update", { id: event.id }),
        method: "PATCH",
        params: event,
      }),
      invalidatesTags: (result) => [
        { type: "Event", id: result?.id },
        { type: "Event", id: `LIST${result?.slot_id}` },
        { type: "Event", id: "LIST" },
      ],
    }),
    getLocations: builder.query<Location[], void>({
      query: () => routeSwitcher("locations"),
      providesTags: (result) =>
        result
          ? [
              ...result.map(({ id }) => ({ type: "Location", id } as const)),
              { type: "Location", id: "LIST" },
            ]
          : [{ type: "Location", id: "LIST" }],
    }),
    updateLocation: builder.mutation<Location, Location>({
      query: (location) => ({
        url: routeSwitcher("location.update", { id: location.id }),
        method: "PATCH",
        params: location,
      }),
      invalidatesTags: (result) => [
        { type: "Location", id: result?.id },
        { type: "Location", id: "LIST" },
      ],
    }),
  }),
});
