import { RequiredAndOmitFields } from "types/misc";
import { Location } from "types/models";

import routeSwitcher from "lib/route";

import baseAPI from ".";

export const locationAPI = baseAPI
    .enhanceEndpoints({
        addTagTypes: ["Location"],
    })
    .injectEndpoints({
        endpoints: (builder) => ({
            getLocations: builder.query<Omit<Location, "events">[], void>({
                query: () => routeSwitcher("locations.index"),
                providesTags: (res, err, arg) =>
                    res
                        ? [
                              ...res.map(
                                  ({ id }) =>
                                      ({ type: "Location", id }) as const,
                              ),
                              { type: "Location", id: "LIST" },
                          ]
                        : [{ type: "Location", id: "LIST" }],
            }),
            getLocation: builder.query<
                RequiredAndOmitFields<Location, "events", "currentEvent">,
                number
            >({
                query: (id) => routeSwitcher("location.show", { id }),
                providesTags: (res) =>
                    res
                        ? [{ type: "Location", id: res.id }]
                        : [{ type: "Location", id: "LIST" }],
            }),
            updateLocation: builder.mutation<
                Omit<Location, "currentEvent" | "events">,
                Omit<Location, "currentEvent" | "events">
            >({
                query: (location) => ({
                    url: routeSwitcher("location.update", { id: location.id }),
                    method: "PATCH",
                    params: location,
                }),
                invalidatesTags: (res, err, arg) =>
                    err
                        ? []
                        : [
                              { type: "Location", id: res?.id },
                              { type: "Location", id: "LIST" },
                          ],
            }),
        }),
    });

export default locationAPI;
