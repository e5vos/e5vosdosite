import { SerializedError } from "@reduxjs/toolkit";
import { FetchBaseQueryError } from "@reduxjs/toolkit/dist/query";
import { api } from "lib/api";
import { useDispatch, useSelector } from "lib/store";
import { useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { authSlice } from "reducers/authReducer";

const useUser = (redirect: boolean = true, destination?: string) => {
  const navigate = useNavigate();
  const {
    data: user,
    error,
    isLoading,
    isFetching,
    ...rest
  } = api.useGetUserDataQuery();
  const location = useLocation();
  const token = useSelector((state) => state.auth.token);
  const dispatch = useDispatch();

  useEffect(() => {
    let redirectToLogin: string | undefined;
    let redirectToStudentCode: string | undefined;
    if (!redirect) {
      redirectToLogin = undefined;
      redirectToStudentCode = undefined;
    } else if (destination) {
      redirectToLogin = destination;
      redirectToStudentCode = destination;
    } else {
      redirectToLogin = "/login?next=" + location.pathname;
      redirectToStudentCode = "/studentcode?next=" + location.pathname;
    }

    if (error && "status" in error && error.status === 401 && redirectToLogin) {
      if (token) dispatch(authSlice.actions.setToken(""));
      navigate(redirectToLogin);
    }

    if (user && !user.e5code) {
      if (redirectToStudentCode) navigate(redirectToStudentCode);
    }
  }, [
    user,
    error,
    navigate,
    token,
    dispatch,
    redirect,
    destination,
    location.pathname,
  ]);

  return { user, error, isLoading, isFetching, ...rest };
};

export default useUser;
