import { Permission, Slot, User, UserStub } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const adminAPI = baseAPI
    .enhanceEndpoints({
        addTagTypes: ["Event", "Slot", "EventParticipants"],
    })
    .injectEndpoints({
        endpoints: (builder) => ({
            getUsers: builder.query<UserStub[], void>({
                query: () => routeSwitcher("users.index"),
            }),
            getFreeUsers: builder.query<UserStub[], Pick<Slot, "id">>({
                query: (slot) =>
                    routeSwitcher("slot.free_students", { slotId: slot }),
            }),
            getNotPresentUsers: builder.query<UserStub[], Pick<Slot, "id">>({
                query: (slot) =>
                    routeSwitcher("slot.not_attending_students", {
                        slotId: slot,
                    }),
            }),
            getPresentUsers: builder.query<UserStub[], Pick<User, "id">>({
                query: (slot) =>
                    routeSwitcher("slot.attending_students", { slotId: slot }),
            }),

            createSlot: builder.mutation<
                Omit<Slot, "events">,
                Omit<Slot, "id" | "events">
            >({
                query: (slot) => ({
                    url: routeSwitcher("slot.store"),
                    method: "POST",
                    params: slot,
                }),
                invalidatesTags: [{ type: "Slot", id: "LIST" }],
            }),

            deleteSlot: builder.mutation<
                Omit<Slot, "events">,
                Pick<Slot, "id">
            >({
                query: (slotId) => ({
                    url: routeSwitcher("slot.destroy", { slotId }),
                    method: "DELETE",
                }),
            }),

            updateSlot: builder.mutation<Omit<Slot, "events">, Slot>({
                query: (slot) => ({
                    url: routeSwitcher("slot.update", { slotId: slot.id }),
                    method: "PUT",
                    params: slot,
                }),
                invalidatesTags: (result, error, { id }) => [
                    { type: "Slot", id },
                    { type: "Slot", id: "LIST" },
                    { type: "Event", id: `LIST${id}` },
                ],
            }),

            clearCache: builder.mutation<void, void>({
                query: () => ({
                    url: routeSwitcher("cacheclear"),
                    method: "POST",
                }),
                invalidatesTags: ["Event", "Slot", "EventParticipants", "User"],
            }),
            createPermission: builder.mutation<Permission, Permission>({
                query: (data) => ({
                    url: routeSwitcher("permission.store"),
                    method: "POST",
                    body: data,
                }),
                invalidatesTags: (result, error, arg) =>
                    arg.event_id
                        ? [
                              { type: "User", id: arg.user_id },
                              { type: "Event", id: arg.event_id },
                          ]
                        : [{ type: "User", id: arg.user_id }],
            }),
            deletePermission: builder.mutation<void, Permission>({
                query: (data) => ({
                    url: routeSwitcher("permission.delete"),
                    method: "DELETE",
                    body: data,
                }),
                invalidatesTags: (result, error, arg) =>
                    arg.event_id
                        ? [
                              { type: "User", id: arg.user_id },
                              { type: "Event", id: arg.event_id },
                          ]
                        : [{ type: "User", id: arg.user_id }],
            }),
        }),
    });

export default adminAPI;
