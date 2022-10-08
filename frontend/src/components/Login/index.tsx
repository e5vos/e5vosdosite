import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";

const LoginForm = () => {
  return (
    <Form className="max-w-xs mx-auto">
      <Form.Group>
        <Form.Label>Felhasználónév</Form.Label>
        <Form.Control type="text" placeholder="Username" />
      </Form.Group>
      <Form.Group>
        <Form.Label>Jelszó</Form.Label>
        <Form.Control type="password" placeholder="Username" />
      </Form.Group>
      <Button variant="submit" className="mx-auto">
        Bejelentkezés
      </Button>
    </Form>
  );
};

export default LoginForm;
