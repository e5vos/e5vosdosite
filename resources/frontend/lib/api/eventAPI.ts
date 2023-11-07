import { RequiredFields } from "types/misc";
import {
    Attender,
    Event,
    EventStub,
    Presentation,
    Rating,
    Slot,
    TeamAttendance,
    TeamMemberAttendance,
    UserAttendance,
} from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const eventAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getEvents: builder.query<
            EventStub[] | undefined,
            Pick<Slot, "id"> | void
        >({
            query: (slot?) =>
                slot
                    ? routeSwitcher("events.slot", { slot_id: slot.id })
                    : routeSwitcher("events.index"),
            transformResponse: (response: any) => {
                if (!Array.isArray(response)) return [response];
                else return response;
            },
            providesTags: (result) => {
                if (result) {
                    return [
                        ...result.map(
                            ({ id }) => ({ type: "Event", id: id }) as const,
                        ),
                        ...result.map(
                            ({ slot }) =>
                                ({
                                    type: "Event",
                                    id: `LIST${slot}`,
                                }) as const,
                        ),
                        { type: "Event", id: "LIST" },
                    ];
                } else {
                    return [{ type: "Event", id: "LIST" }];
                }
            },
        }),
        getEvent: builder.query<
            RequiredFields<Event, "slot" | "location">,
            Pick<Event, "id">
        >({
            query: ({ id }) => routeSwitcher("event.show", { eventId: id }),
            providesTags: (result, error, { id }) => [{ type: "Event", id }],
        }),
        getEventParticipants: builder.query<Attender[], Pick<Event, "id">>({
            query: ({ id }) => routeSwitcher("event.participants", id),
            providesTags: (result, error, { id }) => [
                { type: "EventParticipants", id },
            ],
        }),
        getSlots: builder.query<Omit<Slot, "events">[], void>({
            query: () => routeSwitcher("slot.index"),
            transformResponse: (response: any) => {
                if (!Array.isArray(response)) return [response];
                else return response;
            },
            providesTags: (result) => {
                if (result) {
                    return [
                        ...result.map(
                            ({ id }) => ({ type: "Slot", id: id }) as const,
                        ),
                        { type: "Slot", id: "LIST" },
                    ];
                } else return [{ type: "Slot", id: "LIST" }];
            },
        }),
        createEvent: builder.mutation<
            Event,
            Omit<Event, "id" | "occupancy" | "">
        >({
            query: (event) => ({
                url: routeSwitcher("events"),
                method: "POST",
                body: event,
            }),
            invalidatesTags: (result) => [
                { type: "Event", id: `LIST${result?.slot_id}` },
                { type: "Event", id: "LIST" },
            ],
        }),
        editEvent: builder.mutation<Event, Omit<Event, "occupancy">>({
            query: (event) => ({
                url: routeSwitcher("event.update", { id: event.id }),
                method: "PUT",
                body: event,
            }),
            invalidatesTags: (result) => [
                { type: "Event", id: result?.id },
                { type: "Event", id: `LIST${result?.slot_id}` },
                { type: "Event", id: "LIST" },
            ],
        }),
        getUsersPresentations: builder.query<Presentation[], void>({
            query: () => routeSwitcher("events.mypresentations"),
            providesTags: (result) =>
                result
                    ? [
                          ...result.map(
                              ({ id }) => ({ type: "Event", id }) as const,
                          ),
                          { type: "Event", id: "MYLIST" },
                      ]
                    : [{ type: "Event", id: "MYLIST" }],
        }),
        signUp: builder.mutation<
            | RequiredFields<TeamAttendance, "team">
            | RequiredFields<UserAttendance, "user">,
            { event: Pick<Event, "id">; attender: string | number }
        >({
            query: (body) => ({
                url: routeSwitcher("event.signup", { id: body.event.id }),
                method: "POST",
                body: { attender: body.attender },
            }),
            invalidatesTags: (res, err, arg) =>
                err
                    ? []
                    : [
                          {
                              type: "EventParticipants",
                              id: arg.event.id,
                          },
                      ],
        }),
        attend: builder.mutation<
            | RequiredFields<TeamAttendance, "team">
            | RequiredFields<UserAttendance, "user">,
            {
                event: Pick<Event, "id">;
                attender: string | number;
                present?: boolean | undefined;
            }
        >({
            query: (body) => ({
                url: routeSwitcher("event.attend", {
                    eventId: body.event.id,
                }),
                method: "POST",
                body: {
                    attender: body.attender,
                    toogle: body.present,
                },
            }),
            invalidatesTags: (res, err, arg) =>
                err ? [] : [{ type: "EventParticipants", id: arg.event.id }],
        }),
        teamMemberAttend: builder.mutation<
            TeamMemberAttendance[],
            { data: TeamMemberAttendance[] }
        >({
            query: ({ data }) => ({
                url: routeSwitcher("attendance.teamMemberAttend", {
                    attendanceId: data.length > 0 ? data[0].attendance_id : -1,
                }),
                method: "POST",
                body: {
                    memberAttendances: JSON.stringify(data),
                },
            }),
            invalidatesTags: (res, err, arg) => (err ? [] : []),
        }),
        cancelSignUp: builder.mutation<
            void,
            { attender: string; event: Pick<Event, "id"> }
        >({
            query: (body) => ({
                url: routeSwitcher("event.signup", { id: body.event.id }),
                method: "DELETE",
                body: { attender: body.attender },
            }),
            invalidatesTags: (res, err, arg) =>
                err ? [] : [{ type: "EventParticipants", id: arg.event.id }],
        }),

        deleteEvent: builder.mutation<void, Pick<Event, "id">>({
            query: ({ id }) => ({
                url: routeSwitcher("event.delete", { id }),
                method: "DELETE",
            }),
            invalidatesTags: (res, err, arg) =>
                err ? [] : [{ type: "Event", id: "LIST" }],
        }),

        closeSignUp: builder.mutation<EventStub, Pick<Event, "id">>({
            query: ({ id }) => ({
                url: routeSwitcher("event.close_signup", { id }),
                method: "PUT",
            }),
            invalidatesTags: (res, err, arg) =>
                err
                    ? []
                    : [
                          { type: "Event", id: "LIST" },
                          { type: "Event", id: arg.id },
                      ],
        }),

        updateEvent: builder.mutation<EventStub, Omit<Event, "occupancy">>({
            query: (event) => ({
                url: routeSwitcher("event.update", { id: event.id }),
                method: "PUT",
                body: event,
            }),
            invalidatesTags: (res, err) =>
                err
                    ? []
                    : [
                          { type: "Event", id: res?.id },
                          { type: "Event", id: `LIST${res?.slot_id}` },
                          { type: "Event", id: "LIST" },
                      ],
        }),

        eventSearch: builder.query<EventStub[], string>({
            query: (q) => routeSwitcher("events.index", { q }),
            providesTags: (result) =>
                result
                    ? [
                          ...result.map(
                              ({ id }) => ({ type: "Event", id }) as const,
                          ),
                          { type: "Event", id: "SEARCH" },
                      ]
                    : [{ type: "Event", id: "SEARCH" }],
        }),
        setScore: builder.mutation<
            void,
            {
                event: Pick<Event, "id">;
                attender: string | number;
                rank: number;
            }
        >({
            query: ({ event, attender, rank }) => ({
                url: routeSwitcher("event.score", {
                    eventId: event.id,
                }),
                body: {
                    attender: attender,
                    rank: rank,
                },
            }),
            invalidatesTags: (res, err, arg) =>
                err ? [] : [{ type: "Event", id: arg.event.id }],
        }),
        setRating: builder.mutation<
            Rating,
            { event: Pick<Event, "id">; rating: number }
        >({
            query: ({ event, rating }) => ({
                url: routeSwitcher("event.rate", {
                    eventId: event.id,
                }),
                body: {
                    rating: rating,
                },
            }),
            invalidatesTags: (res, err, arg) =>
                err ? [] : [{ type: "Event", id: arg.event.id }],
        }),
    }),
});

export default eventAPI;
