import {
    Attendance,
    Event,
    Presentation,
    Slot,
    isUserAttendance,
} from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const eventAPI = baseAPI
    .enhanceEndpoints({
        addTagTypes: ["Event", "Slot", "EventParticipants"],
    })
    .injectEndpoints({
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
            getEvent: builder.query<Event, number>({
                query: (id) => routeSwitcher("event.show", id),
                providesTags: (result, error, id) => [{ type: "Event", id }],
            }),
            getEventParticipants: builder.query<Array<Attendance>, number>({
                query: (id) => routeSwitcher("event.participants", id),
                providesTags: (result, error, id) => [
                    { type: "EventParticipants", id },
                ],
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
                            ...result.map(
                                ({ id }) => ({ type: "Slot", id: id }) as const,
                            ),
                            { type: "Slot", id: "LIST" },
                        ];
                    } else return [{ type: "Slot", id: "LIST" }];
                },
            }),
            toggleAttendance: builder.mutation<Attendance, Attendance>({
                query: (data) => {
                    const params = {
                        attender: isUserAttendance(data)
                            ? data.e5code
                            : data.code,
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
        }),
    });

export default eventAPI;
