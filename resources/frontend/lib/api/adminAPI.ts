import { Slot, User } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const adminAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getUsers: builder.query<User[], void>({
            query: () => routeSwitcher("users"),
        }),
        getFreeUsers: builder.query<User[], Pick<Slot, "id">>({
            query: (slot) =>
                routeSwitcher("slot.free_students", { slotId: slot }),
        }),
        getNotPresentUsers: builder.query<User[], Pick<Slot, "id">>({
            query: (slot) =>
                routeSwitcher("slot.not_attending_students", { slotId: slot }),
        }),
        getPresentUsers: builder.query<User[], Pick<User, "id">>({
            query: (slot) =>
                routeSwitcher("slot.attending_students", { slotId: slot }),
        }),

        createSlot: builder.mutation<Slot, Slot>({
            query: (slot) => ({
                url: routeSwitcher("slot.store"),
                method: "POST",
                params: slot,
            }),
        }),

        deleteSlot: builder.mutation<Slot, Pick<Slot, "id">>({
            query: (slotId) => ({
                url: routeSwitcher("slot.destroy", { slotId }),
                method: "DELETE",
            }),
        }),

        updateSlot: builder.mutation<void, Slot>({
            query: (slot) => ({
                url: routeSwitcher("slot.update", { slotId: slot.id }),
                method: "PUT",
                params: slot,
            }),
        }),

        clearCache: builder.mutation<void, void>({
            query: () => ({
                url: routeSwitcher("cache.clear"),
                method: "POST",
            }),
        }),
    }),
});

export default adminAPI;
