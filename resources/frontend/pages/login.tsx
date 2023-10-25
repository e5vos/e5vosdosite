import useUser from "hooks/useUser";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import { isTeacher } from "lib/gates";
import { useSelector } from "lib/store";

import LoginForm from "components/Login";
import Button from "components/UIKit/Button";
import Loader from "components/UIKit/Loader";

const LoginRecoveryPanel = () => {
    const { user } = useUser();
    const navigate = useNavigate();
    const params = useParams();
    const token = useSelector((state) => state.auth.token);
    useEffect(() => {
        if (token !== "") {
            navigate(params.next ?? "/eloadas", { replace: true });
        }
    }, [navigate, params.next, token]);
    if (!user) return <Loader />;
    return (
        <div>
            <div>Már be vagy jelentkezve</div>
            {isTeacher(user) ? (
                <Button onClick={() => navigate("/eloadas/kezel")}>
                    Előadás Jelenlétí Ívek
                </Button>
            ) : (
                <Button onClick={() => navigate("/eloadas")}>
                    Előadásjelentkezés
                </Button>
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
