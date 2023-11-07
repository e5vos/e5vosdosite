import { useFormik } from "formik";
import useEventDates from "hooks/useEventDates";
import useUser from "hooks/useUser";
import * as Yup from "yup";

import { CRUDForm } from "types/misc";
import { SignupType } from "types/models";

import eventAPI from "lib/api/eventAPI";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";
import { formatDateTimeInput } from "lib/util";

import { EventFormValues } from "components/Event/CRUD";
import LocationSearchCombobox from "components/Location/LocationSelect";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const locale = Locale({
    hu: {
        submit: "Mentés",
        required: "Kötelező mező",
        fields: {
            name: "Név",
            description: "Leírás",
            starts_at: "Kezdés",
            ends_at: "Befejezés",
            signup_deadline: "Jelentkezési határidő",
            organiser: "Szervező",
            location: "Helyszín",
            capacity: "Kapacitás",
            is_competition: "Verseny?",
            slot: "Sáv",
            signup_type: "Jelentkezés típusa",
        },
        noslot: "Nincs sáv",
        signup_type: (type: SignupTypeType): string => {
            switch (type) {
                case SignupType.Individual:
                    return "Egyéni jelentkezés";
                case SignupType.Team:
                    return "Csapatos jelentkezés";
                case SignupType.Both:
                    return "Egyéni és csapatos jelentkezés";
            }
        },
    },
    en: {
        submit: "Submit",
        required: "Required",
        fields: {
            name: "Name",
            description: "Description",
            starts_at: "Starts at",
            ends_at: "Ends at",
            signup_deadline: "Signup deadline",
            organiser: "Organiser",
            location: "Location",
            capacity: "Capacity",
            is_competition: "Is competition?",
            slot: "Slot",
            signup_type: "Signup type",
        },
        noslot: "No slot",
        signup_type: (type: SignupTypeType): string => {
            switch (type) {
                case SignupType.Individual:
                    return "Individual signup";
                case SignupType.Team:
                    return "Team signup";
                case SignupType.Both:
                    return "Individual and team signup";
            }
        },
    },
});

const EventForm = ({
    initialValues,
    onSubmit,
    submitLabel = locale.submit,
    enableReinitialize = false,
    resetOnSubmit = false,
    ...rest
}: CRUDForm<EventFormValues>) => {
    const { user } = useUser(false);
    const { starts_at, ends_at, signup_deadline, now } =
        useEventDates(initialValues);
    const { data: slots } = eventAPI.useGetSlotsQuery();
    const formik = useFormik({
        initialValues: {
            ...initialValues,
            starts_at: formatDateTimeInput(starts_at ?? now),
            ends_at: formatDateTimeInput(ends_at ?? now),
            signup_deadline: signup_deadline
                ? formatDateTimeInput(signup_deadline)
                : "",
            signup_enabled: Boolean(signup_deadline),
            capacity_enabled: Boolean(initialValues.capacity),
            slot_id: initialValues.slot_id,
            signup_type: initialValues.signup_type,
        },
        validationSchema: Yup.object({
            name: Yup.string().required(locale.required),
            description: Yup.string().required(locale.required),
            starts_at: Yup.string().required(locale.required),
            ends_at: Yup.string().required(locale.required),
            signup_deadline: Yup.string(),
            signup_enabled: Yup.boolean().required(locale.required),
            organiser: Yup.string().required(locale.required),
            capacity: Yup.number().nullable(),
            is_competition: Yup.boolean(),
            capacity_enabled: Yup.boolean(),
            slot_id: Yup.number(),
            signup_type: Yup.string(),
        }),
        onSubmit: (values) => {
            const val = onSubmit({
                id: initialValues.id,
                name: values.name,
                description: values.description,
                starts_at: values.starts_at,
                ends_at: values.ends_at,
                organiser: values.organiser,
                location_id: values.location_id,
                is_competition: values.is_competition,
                signup_type: values.signup_type,
                slot_id: values.slot_id !== -1 ? values.slot_id : null,

                signup_deadline: values.signup_enabled
                    ? values.signup_deadline
                    : null,
                capacity: values.capacity_enabled ? values.capacity : null,
            });
            if (resetOnSubmit) formik.resetForm();
            return val;
        },
        enableReinitialize: enableReinitialize,
    });
    console.log(formik.errors);
    return (
        <Form onSubmit={formik.handleSubmit} {...rest}>
            <Form.Group>
                <Form.Label>{locale.fields.name}</Form.Label>
                <Form.Control
                    name="name"
                    value={formik.values.name}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.name)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.fields.description}</Form.Label>
                <Form.Control
                    name="description"
                    value={formik.values.description}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.description)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.fields.starts_at}</Form.Label>
                <Form.Control
                    name="starts_at"
                    type="datetime-local"
                    value={formik.values.starts_at}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.starts_at)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.fields.ends_at}</Form.Label>
                <Form.Control
                    name="ends_at"
                    type="datetime-local"
                    value={formik.values.ends_at}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.ends_at)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.fields.signup_deadline}</Form.Label>
                <div className="flex w-full flex-row gap-4 align-middle">
                    <Form.Control
                        name="ends_at"
                        type="datetime-local"
                        value={formik.values.signup_deadline}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.signup_deadline)}
                    />
                    <Form.Check
                        name="signup_enabled"
                        checked={formik.values.signup_enabled}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                    />
                </div>
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.fields.organiser}</Form.Label>
                <Form.Control
                    name="organiser"
                    value={formik.values.organiser}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    invalid={Boolean(formik.errors.organiser)}
                />
            </Form.Group>
            {isAdmin(user) && (
                <Form.Group>
                    <Form.Label>{locale.fields.location}</Form.Label>
                    <LocationSearchCombobox
                        onChange={(e) =>
                            formik.setFieldValue("location_id", e.id)
                        }
                    />
                </Form.Group>
            )}
            {isAdmin(user) && (
                <Form.Group>
                    <Form.Label>{locale.fields.capacity}</Form.Label>
                    <div className="flex w-full flex-row gap-4 align-middle">
                        <Form.Control
                            type="number"
                            name="capacity"
                            value={formik.values.capacity ?? undefined}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            invalid={Boolean(formik.errors.capacity)}
                        />
                        <Form.Check
                            name="capacity_enabled"
                            checked={formik.values.capacity_enabled}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                        />
                    </div>
                </Form.Group>
            )}
            {isAdmin(user) && (
                <Form.Group>
                    <Form.Label>{locale.fields.slot}</Form.Label>
                    <Form.Select
                        name="slot_id"
                        value={formik.values.slot_id ?? "-1"}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                    >
                        <option value="-1">{locale.noslot}</option>
                        {slots?.map((slot) => (
                            <option key={slot.id} value={slot.id}>
                                {slot.name}
                            </option>
                        ))}
                    </Form.Select>
                </Form.Group>
            )}
            {isAdmin(user) && (
                <Form.Group>
                    <Form.Label>{locale.fields.signup_type}</Form.Label>
                    <Form.Select
                        name="signup_type"
                        value={formik.values.signup_type}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                    >
                        {Object.entries(SignupType).map(([key, value]) => (
                            <option value={value} key={key}>
                                {locale.signup_type(value)}
                            </option>
                        ))}
                    </Form.Select>
                </Form.Group>
            )}
            {isAdmin(user) && (
                <Form.Group className="">
                    <div className="flex flex-row gap-3 align-middle">
                        <Form.Label className="h-full">
                            {locale.fields.is_competition}
                        </Form.Label>
                        <Form.Check
                            name="is_competition"
                            checked={formik.values.is_competition}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            invalid={Boolean(formik.errors.is_competition)}
                        />
                    </div>
                </Form.Group>
            )}

            <Form.Group>
                <Button type="submit">{locale.submit}</Button>
            </Form.Group>
        </Form>
    );
};

export default EventForm;
