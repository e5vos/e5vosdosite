import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import Cookies from "js-cookie";

import { RequiredAndOmitFields, RequiredFields } from "types/misc";
import { User, UserStub } from "types/models";

import routeSwitcher from "../route";
import { RootState } from "../store";

export const baseAPI = createApi({
    reducerPath: "e5nApi",
    baseQuery: fetchBaseQuery({
        fetchFn(input, init?) {
            if (input instanceof Request && input.url.includes("/-1/")) {
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
    tagTypes: ["User"],
    endpoints: (builder) => ({
        getCurrentUserData: builder.query<
            RequiredAndOmitFields<User, "permissions", "teams" | "activity">,
            void
        >({
            // return type depends zoli TODO
            query: (data) => ({
                url: routeSwitcher("user.current"),
                method: "GET",
            }),
            providesTags: (result) => [{ type: "User", id: result?.id }],
        }),
        getCurrentUserDetails: builder.query<User, void>({
            query: (data) => ({
                url: routeSwitcher("user.details"),
                method: "GET",
            }),
            providesTags: (result) => [{ type: "User", id: result?.id }],
        }),
        setStudentCode: builder.mutation<User, string>({
            query: (code) => ({
                url: routeSwitcher("user.e5code"),
                method: "PATCH",
                params: { e5code: code },
            }),
        }),
        userSearch: builder.query<UserStub[], string | number>({
            query: (q) => ({
                url: routeSwitcher("users.index"),
                params: q ? { q } : undefined,
            }),
        }),
        getUser: builder.query<RequiredFields<User, "teams">, Pick<User, "id">>(
            {
                query: ({ id }) => ({
                    url: routeSwitcher("users.show", { userId: id }),
                }),
                providesTags: (result) => [{ type: "User", id: result?.id }],
            },
        ),
    }),
});
export default baseAPI;
