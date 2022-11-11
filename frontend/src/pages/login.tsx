import LoginForm from "components/Login";
import Button from "components/UIKit/Button";
import Loader from "components/UIKit/Loader";
import useUser from "hooks/useUser";
import refreshCSRF from "lib/csrf";
import { isTeacher } from "lib/gates";
import { useSelector } from "lib/store";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

const LoginRecoveryPanel = () => {
  const { user } = useUser();
  const navigate = useNavigate();
  if (!user) return <Loader />;
  return (
    <div>
      <div>Már be vagy jelentkezve</div>
      {isTeacher(user) ? (
        <Button onClick={() => navigate("/eloadas/kezel")}>
          Előadás Jelenlétí Ívek
        </Button>
      ) : (
        <Button onClick={() => navigate("/eloadas")}>Előadásjelentkezés</Button>
      )}
    </div>
  );
};

const LoginPage = () => {
  const { user } = useUser(false);

  return (
    <div className="mx-auto text-center">
      <h1 className="mb-10 text-4xl font-bold ">Bejelentkezés</h1>
      {user ? <LoginRecoveryPanel /> : <LoginForm />}
    </div>
  );
};

export default LoginPage;
