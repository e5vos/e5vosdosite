import {
    Attendance,
    Event,
    EventStub,
    Presentation,
    Slot,
    TeamMemberAttendance,
} from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const eventAPI = baseAPI
    .enhanceEndpoints({
        addTagTypes: ["Event", "Slot", "EventParticipants"],
    })
    .injectEndpoints({
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
                                ({ id }) =>
                                    ({ type: "Event", id: id }) as const,
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
            getEvent: builder.query<EventStub, Pick<Event, "id">>({
                query: ({ id }) => routeSwitcher("event.show", { eventId: id }),
                providesTags: (result, error, { id }) => [
                    { type: "Event", id },
                ],
            }),
            getEventParticipants: builder.query<
                Attendance[],
                Pick<Event, "id">
            >({
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
                    params: event,
                }),
                invalidatesTags: (result) => [
                    { type: "Event", id: `LIST${result?.slot_id}` },
                    { type: "Event", id: "LIST" },
                ],
            }),
            editEvent: builder.mutation<Event, Omit<Event, "occupancy">>({
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
            attend: builder.mutation<
                Attendance,
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
                    params: {
                        attender: body.attender,
                        toogle: body.present,
                    },
                }),
            }),
            teamMemberAttend: builder.mutation<
                TeamMemberAttendance[],
                TeamMemberAttendance[]
            >({
                query: (data) => ({
                    url: routeSwitcher("team.member.attend"),
                    method: "POST",
                    params: data,
                }),
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

            deleteEvent: builder.mutation<void, Pick<Event, "id">>({
                query: ({ id }) => ({
                    url: routeSwitcher("event.delete", { id }),
                    method: "DELETE",
                }),
                invalidatesTags: (result) => [{ type: "Event", id: "LIST" }],
            }),

            closeSignUp: builder.mutation<EventStub, Pick<Event, "id">>({
                query: ({ id }) => ({
                    url: routeSwitcher("event.close_signup", { id }),
                    method: "PUT",
                }),
                invalidatesTags: (result) => [{ type: "Event", id: "LIST" }],
            }),

            updateEvent: builder.mutation<EventStub, Omit<Event, "occupancy">>({
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
        }),
    });

export default eventAPI;
