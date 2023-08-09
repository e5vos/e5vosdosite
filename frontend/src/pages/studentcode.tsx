import { useFormik } from "formik";
import useUser from "hooks/useUser";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import * as Yup from "yup";

import baseAPI from "lib/api";

import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const StudentCodePage = () => {
  const [updateStudentCode] = baseAPI.useSetStudentCodeMutation();
  const navigate = useNavigate();
  const { next } = useParams();
  const { user, error, isLoading } = useUser(false);

  useEffect(() => {
    if (!user && error) {
      navigate("/login?next=/studentcode");
    }
    if (user?.e5code) {
      navigate(next ?? "/");
    }
  }, [user, error, navigate, next]);

  const formik = useFormik({
    initialValues: {
      studentCode: "",
    },
    validationSchema: Yup.object({
      studentCode: Yup.string()
        .matches(
          /^20(\d{2})([A-FN])(\d{2})EJG(\d{3})$/,
          "Diákkódnak kell lennie"
        )
        .required("Diákkód megadása kötelező"),
    }),
    validateOnChange: false,
    onSubmit: async (values) => {
      try {
        await updateStudentCode(values.studentCode).unwrap();
        navigate(next ?? "/dashboard");
      } catch (error: any) {
        if (error.status === 400) {
          formik.setFieldError("studentCode", "Hibás diákkód");
        }
      }
    },
  });
  if (isLoading) return <Loader />;
  return (
    <div className="container mx-auto">
      <div className="mx-auto max-w-xl text-center">
        <h1 className="mb-2 text-4xl font-bold">Diákkód megadása</h1>
        <hr className="mb-3 bg-gray-50" />
        <Form onSubmit={formik.handleSubmit}>
          <Form.Group>
            <Form.Label className="text-4xl">Diákkód:</Form.Label>
            <Form.Control
              name="studentCode"
              type="text"
              onChange={formik.handleChange}
              onBlur={formik.handleBlur}
              className="text-center"
              invalid={!!formik.errors.studentCode}
            />
            <Form.Text className="text-red-300">
              {formik.errors.studentCode}
            </Form.Text>
          </Form.Group>
          <Form.Group>
            <Button type="submit">Diákkód jóváhagyása</Button>
          </Form.Group>
        </Form>
      </div>
    </div>
  );
};

export default StudentCodePage;
