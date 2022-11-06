import Cookies from "js-cookie";
import { authSlice } from "reducers/authReducer";

const refreshCSRF = async () => {
  await fetch(`${import.meta.env.VITE_BACKEND}/sanctum/csrf-cookie`, {
    credentials: "include",
  });
  return Cookies.get("XSRF-TOKEN");
};

export default refreshCSRF;
