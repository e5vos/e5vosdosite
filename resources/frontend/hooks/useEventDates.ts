import { useMemo } from "react";

import { Event } from "types/models";

type EventDateInput = Pick<Event, "starts_at" | "ends_at" | "signup_deadline">;

export const calculateEventDates = (event: EventDateInput | undefined) => ({
    now: new Date(),
    starts_at: event?.starts_at ? new Date(event.starts_at) : undefined,
    ends_at: event?.ends_at ? new Date(event.ends_at) : undefined,
    signup_deadline: event?.signup_deadline
        ? new Date(event.signup_deadline)
        : undefined,
});

const useEventDates = (event: EventDateInput | undefined) =>
    useMemo(() => calculateEventDates(event), [event]);
export default useEventDates;
