import { useFormik } from "formik";
import { useParams } from "react-router-dom";
import * as Yup from "yup";

import { Location } from "types/models";

import { api } from "lib/api";
import { isOperator } from "lib/gates";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

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

const testLocs: { data: Location[] } = {
  data: [
    {
      id: 1,
      name: "Budapest",
    },
    {
      id: 2,
      name: "Debrecen",
    },
  ],
};

const EditEventPage = () => {
  const { eventid } = useParams();
  const { data: event, isLoading: isEventLoading } = api.useGetEventQuery(
    Number(eventid)
  );
  const { data: locations } = testLocs; //api.useGetLocationsQuery();
  const [updateEvent] = api.useUpdateEventMutation();

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
  console.log(event, locations);
  if (!event || !locations) return <Loader />;
  return (
    <div className="container mx-auto">
      <h1>EditEventPage</h1>
      <Form onSubmit={formik.handleSubmit}>
        <Form.Group>
          <Form.Label>Event Name</Form.Label>
          <Form.Control
            name="name"
            value={formik.values.name}
            onChange={formik.handleChange}
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event Description</Form.Label>
          <Form.Control
            name="description"
            value={formik.values.description}
            onChange={formik.handleChange}
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event Starts At</Form.Label>
          <Form.Control
            name="starts_at"
            value={formik.values.starts_at}
            onChange={formik.handleChange}
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event Ends At</Form.Label>
          <Form.Control
            name="ends_at"
            value={formik.values.ends_at}
            onChange={formik.handleChange}
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event Organiser</Form.Label>
          <Form.Control
            name="organiser"
            value={formik.values.organiser}
            onChange={formik.handleChange}
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event Location</Form.Label>
          <Form.ComboBox<Location>
            options={locations}
            filter={(s) => (e) =>
              e.name.toLowerCase().includes(s.toLowerCase())}
            renderElement={(loc) => loc.name}
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event Capacity</Form.Label>
          <Form.Control
            name="capacity"
            value={formik.values.capacity}
            onChange={formik.handleChange}
            type="number"
          />
        </Form.Group>
        <Form.Group>
          <Form.Label>Event is Competition</Form.Label>
          <Form.Check
            name="is_competition"
            checked={formik.values.is_competition}
            onChange={formik.handleChange}
          />
        </Form.Group>

        <Form.Group>
          <Button type="submit">Submit</Button>
        </Form.Group>
      </Form>
    </div>
  );
};

export default gated(EditEventPage, isOperator);
