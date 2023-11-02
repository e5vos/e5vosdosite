import { useFormik } from "formik";
import { useParams } from "react-router-dom";
import * as Yup from "yup";

import { Location } from "types/models";

import eventAPI from "lib/api/eventAPI";
import locationAPI from "lib/api/locationAPI";
import { isOperator } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const locale = Locale({
    hu: {
        title: "Esemény szerkesztése",
        event: {
            name: "Esemény neve",
            description: "Esemény leírása",
            starts_at: "Esemény kezdete",
            ends_at: "Esemény vége",
            organiser: "Szervező",
            location: "Helyszín",
            capacity: "Kapacitás",
            is_competition: "Verseny",
        },
        submit: "Szerkesztés",
    },
    en: {
        title: "Edit event",
        event: {
            name: "Event name",
            description: "Event description",
            starts_at: "Event start",
            ends_at: "Event end",
            organiser: "Organiser",
            location: "Location",
            capacity: "Capacity",
            is_competition: "Competition",
        },
        submit: "Edit",
    },
});

const initialValues: {
    name: string;
    description: string;
    starts_at: string;
    ends_at: string;
    organiser: string;
    location_id: number | null;
    is_competition: boolean;
    capacity: string | number;
} = {
    name: "",
    description: "",
    starts_at: "",
    ends_at: "",
    organiser: "",
    location_id: null,
    is_competition: false,
    capacity: "Korlátlan",
};

const EditEventPage = () => {
    const { eventid } = useParams();
    const { data: event } = eventAPI.useGetEventQuery({ id: Number(eventid) });
    const { data: locations } = locationAPI.useGetLocationsQuery();
    const [updateEvent] = eventAPI.useUpdateEventMutation();

    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            id: Yup.number().required(),
            name: Yup.string().required("Kötelező mező"),
        }),
        onSubmit: async (values) => {
            if (!event) return;
            let newEvent = event;
            if (values.capacity === "Korlátlan") newEvent.capacity = null;
            updateEvent(newEvent);
        },
    });
    console.log(event);
    if (!event) return <Loader />;
    if (!locations) return <Loader />;
    return (
        <div className="container mx-auto">
            <h1 className="text-center text-4xl font-bold">{locale.title}</h1>
            <Form onSubmit={formik.handleSubmit}>
                <Form.Group>
                    <Form.Label>{locale.event.name}</Form.Label>
                    <Form.Control
                        name="name"
                        value={formik.values.name}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.name)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.description}</Form.Label>
                    <Form.Control
                        name="description"
                        value={formik.values.description}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.description)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.starts_at}</Form.Label>
                    <Form.Control
                        name="starts_at"
                        value={formik.values.starts_at}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.starts_at)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.ends_at}</Form.Label>
                    <Form.Control
                        name="ends_at"
                        value={formik.values.ends_at}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.ends_at)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.organiser}</Form.Label>
                    <Form.Control
                        name="organiser"
                        value={formik.values.organiser}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.organiser)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.location}</Form.Label>
                    <Form.ComboBox<Location>
                        options={locations}
                        filter={(s) => (e) =>
                            e.name.toLowerCase().includes(s.toLowerCase())
                        }
                        renderElement={(loc) => loc.name}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.capacity}</Form.Label>
                    <Form.Control
                        type="number"
                        name="capacity"
                        value={formik.values.capacity}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.capacity)}
                    />
                </Form.Group>
                <Form.Group>
                    <Form.Label>{locale.event.is_competition}</Form.Label>
                    <Form.Check
                        name="is_competition"
                        checked={formik.values.is_competition}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        invalid={Boolean(formik.errors.is_competition)}
                    />
                </Form.Group>

                <Form.Group>
                    <Button type="submit">{locale.submit}</Button>
                </Form.Group>
            </Form>
        </div>
    );
};

export default gated(EditEventPage, isOperator);
