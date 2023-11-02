import { useMemo } from "react";

import { Event } from "types/models";

export const calculateEventDates = (event: Event | undefined) => ({
    now: new Date(),
    starts_at: event?.starts_at ? new Date(event.starts_at) : undefined,
    ends_at: event?.ends_at ? new Date(event.ends_at) : undefined,
    signup_deadline: event?.signup_deadline
        ? new Date(event.signup_deadline)
        : undefined,
});

const useEventDates = (event: Event | undefined) =>
    useMemo(() => calculateEventDates(event), [event]);
export default useEventDates;
