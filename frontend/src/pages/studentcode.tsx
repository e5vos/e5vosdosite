import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";
import { useFormik } from "formik";
import useUser from "hooks/useUser";
import { api } from "lib/api";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import * as Yup from "yup";

const StudentCodePage = () => {
  const [updateStudentCode, { error: apiError }] =
    api.useSetStudentCodeMutation();
  const navigate = useNavigate();
  const { next } = useParams();
  const { user, error, isLoading } = useUser(false);

  useEffect(() => {
    if (!user && error) {
      navigate("/login?next=/studentcode");
    }
    if (user && user.e5code) {
      navigate(next || "/");
    }
  }, [user, error, navigate, next]);

  const formik = useFormik({
    initialValues: {
      studentCode: "",
    },
    validationSchema: Yup.object({
      studentCode: Yup.string()
        .matches(
          /^20([1-2]\d)([A-FN]{1})(\d{2})EJG(\d{3})$/,
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
      <div className="text-center max-w-xl mx-auto">
        <h1 className="font-bold text-4xl mb-2">Diákkód megadása</h1>
        <hr className="bg-gray-50 mb-3" />
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
