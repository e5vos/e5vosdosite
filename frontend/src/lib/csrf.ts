import { authSlice } from "reducers/authReducer";

const refreshCSRF = async () => {
  const response = await fetch(`${import.meta.env.VITE_BACKEND}/api/csrf`, {
    method: "POST",
    credentials: "include",
  });
  if (response.ok) {
    const csrf = await response.text();
    authSlice.actions.setCSRF(csrf);
  }
  const { csrfToken } = await response.json();
  return csrfToken;
};
