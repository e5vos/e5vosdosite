import LoginForm from "components/Login";
import refreshCSRF from "lib/csrf";
import { useSelector } from "lib/store";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

const LoginPage = () => {
  const token = useSelector((state) => state.auth.token);
  const navigate = useNavigate();
  const params = useParams();
  useEffect(() => {
    if (token !== "") {
      navigate(params.next ?? "/eloadas", { replace: true });
    }
  }, [navigate, token, params.next]);

  return (
    <div className="mx-auto text-center">
      <h1 className="mb-10 text-4xl font-bold ">Bejelentkezés</h1>
      {token !== "" ? <>Már bejelentkezve</> : <LoginForm />}
    </div>
  );
};

export default LoginPage;
