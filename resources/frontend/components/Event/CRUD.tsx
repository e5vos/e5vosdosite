import useEventDates from "hooks/useEventDates";
import { useCallback } from "react";
import { useNavigate } from "react-router-dom";

import { CRUDFormImpl, CRUDInterface } from "types/misc";
import { Event } from "types/models";

import eventAPI from "lib/api/eventAPI";
import Locale from "lib/locale";
import { formatDateInput } from "lib/util";

import EventForm from "components/Forms/EventForm";

export type EventFormValues = Pick<
    Event,
    | "id"
    | "name"
    | "description"
    | "starts_at"
    | "ends_at"
    | "signup_deadline"
    | "signup_type"
    | "organiser"
    | "location_id"
    | "capacity"
    | "is_competition"
    | "slot_id"
>;

const locale = Locale({
    hu: {
        create: "Esemény létrehozása",
        edit: "Esemény szerkesztése",
    },
    en: {
        create: "Create event",
        edit: "Edit event",
    },
});

const EventCreator = ({
    value,
    ...rest
}: CRUDFormImpl<Event, Partial<EventFormValues>>) => {
    const now = new Date();
    const [createEvent] = eventAPI.useCreateEventMutation();
    const navigate = useNavigate();
    const onSubmit = useCallback(
        async (event: EventFormValues) => {
            try {
                const res = await createEvent(event).unwrap();
                navigate(`/esemeny/${res.id}`);
            } catch (e) {
                console.error(e);
            }
        },
        [createEvent, navigate],
    );
    return (
        <EventForm
            initialValues={{
                id: value.id ?? 0,
                name: value.name ?? "",
                description: value.description ?? "",
                starts_at:
                    value.starts_at ??
                    formatDateInput(
                        value.starts_at ? new Date(value.starts_at) : now,
                    ),
                ends_at:
                    value.ends_at ??
                    formatDateInput(
                        value.ends_at ? new Date(value.ends_at) : now,
                    ),
                signup_deadline: formatDateInput(
                    value.signup_deadline
                        ? new Date(value.signup_deadline)
                        : now,
                ),
                signup_type: value.signup_type ?? "team_user",
                location_id: value.location_id ?? 0,
                organiser: value.organiser ?? "",
                capacity: value.capacity ?? null,
                is_competition: value.is_competition ?? false,
                slot_id: value.slot_id ?? null,
            }}
            onSubmit={onSubmit}
            submitLabel={locale.create}
            resetOnSubmit={true}
            {...rest}
        />
    );
};
const EventUpdater = ({
    value,
    ...rest
}: CRUDFormImpl<Event, EventFormValues>) => {
    const [changeEvent] = eventAPI.useEditEventMutation();
    const initialDates = useEventDates(value);

    return (
        <EventForm
            initialValues={{
                ...value,
                ends_at: formatDateInput(
                    initialDates.ends_at ?? initialDates.now,
                ),
                starts_at: formatDateInput(
                    initialDates.starts_at ?? initialDates.now,
                ),
                signup_deadline: initialDates.signup_deadline
                    ? formatDateInput(initialDates.signup_deadline)
                    : "",
            }}
            onSubmit={changeEvent}
            resetOnSubmit={true}
            submitLabel={locale.edit}
            {...rest}
        ></EventForm>
    );
};

const EventCRUD: CRUDInterface<Event, EventFormValues> = {
    Creator: EventCreator,
    Updater: EventUpdater,
};
export default EventCRUD;
