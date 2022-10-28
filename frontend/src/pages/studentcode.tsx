import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import { useFormik } from "formik";
import useUser from "hooks/useUser";
import { api } from "lib/api";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import * as Yup from "yup";

const StudentCodePage = () => {
  const [updateStudentCode] = api.useSetStudentCodeMutation();
  const navigate = useNavigate();
  const { next } = useParams();
  const { user, error } = useUser(false);

  useEffect(() => {
    if (!user && error) {
      navigate("/login?next=/studentcode");
    }
  }, [user, error, navigate]);

  const formik = useFormik({
    initialValues: {
      studentCode: "",
    },
    validationSchema: Yup.object({
      studentCode: Yup.string()
        .matches(
          /20([1-2]\d)([A-F]{1})(\d{2})EJG(\d{3})/,
          "Diákkódnak kell lennie"
        )
        .required("Student code is required"),
    }),
    onSubmit: async (values) => {
      try {
        await updateStudentCode(values.studentCode).unwrap();
        navigate(next ?? "/dashboard");
      } catch (error: any) {
        formik.errors.studentCode = error;
      }
    },
  });
  return (
    <>
      <Form onSubmit={formik.handleSubmit}>
        <Form.Group>
          <Form.Label>Diákkód:</Form.Label>
          <Form.Control
            name="studentCode"
            type="text"
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
          />
        </Form.Group>
        <Form.Group>
          <Button type="submit">Diákkód jóváhagyása</Button>
        </Form.Group>
      </Form>
    </>
  );
};

export default StudentCodePage;
