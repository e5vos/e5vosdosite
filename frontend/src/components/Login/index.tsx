import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import { useFormik } from "formik";
import * as Yup from "yup"


const LoginForm = () => {
  
  const formik = useFormik({
    initialValues: {
      
    },
    validationSchema: Yup.object({

    }),
    onSubmit: async (values) => {}
  })
  
  return (
    <Form className="max-w-xs mx-auto" onSubmit={formik.handleSubmit}>
      <Form.Group>
        <Form.Label>Felhasználónév</Form.Label>
        <Form.Control type="text" placeholder="Username" />
      </Form.Group>
      <Form.Group>
        <Form.Label>Jelszó</Form.Label>
        <Form.Control type="password" placeholder="Username" />
      </Form.Group>
      <Button variant="success" type="submit" className="mx-auto">
        Bejelentkezés
      </Button>
    </Form>
  );
};

export default LoginForm;
