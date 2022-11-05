import { authSlice } from "reducers/authReducer";

const refreshCSRF = async () => {
  await fetch(`${import.meta.env.VITE_BACKEND}/sanctum/csrf-cookie`, {
    credentials: "include",
  });
};

export default refreshCSRF;
