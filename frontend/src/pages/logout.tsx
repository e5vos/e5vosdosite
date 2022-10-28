import Loader from "components/UIKit/Loader";
import { useDispatch } from "lib/store";
import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { authSlice } from "reducers/authReducer";

const LogoutPage = () => {
  const navigate = useNavigate();
  const dispatch = useDispatch();
  useEffect(() =>{
    dispatch(authSlice.actions.setToken(""));
    navigate("/",{replace: true});
  },[navigate,dispatch])
  return <Loader/>
};

export default LogoutPage;