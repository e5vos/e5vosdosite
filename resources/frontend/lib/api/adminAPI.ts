import { User } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const adminAPI = baseAPI.injectEndpoints({
    endpoints: (builder) => ({
        getUsers: builder.query<User[], void>({
            query: () => routeSwitcher("users"),
        }),
        getFreeUsers: builder.query<User[], number>({
            query: (slot) =>
                routeSwitcher("slot.free_students", { slotId: slot }),
        }),
        getNotPresentUsers: builder.query<User[], number>({
            query: (slot) =>
                routeSwitcher("slot.not_attending_students", { slotId: slot }),
        }),
        getPresentUsers: builder.query<User[], number>({
            query: (slot) =>
                routeSwitcher("slot.attending_students", { slotId: slot }),
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
