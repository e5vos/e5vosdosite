import LoginForm from "components/Login";
import { useSelector } from "lib/store";


const LoginPage = () => {
    const token = useSelector(state => state.auth.token)
    return token !== "" ? "Már bejelentkezve" + token : <LoginForm />
    
}

export default LoginPage;