import LoginForm from "components/Login";
import { useSelector } from "lib/store";
import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

const LoginPage = () => {
  const token = useSelector((state) => state.auth.token);
  const navigate = useNavigate()
  const params = useParams()  
  useEffect(() =>{
    if(token !== "") {
      navigate(params.next ?? "/dashboard", {replace:true});
    }
  },[navigate, token, params.next])
  
  return token !== "" ? <>Már bejelentkezve</> : <LoginForm />;
};

export default LoginPage;
