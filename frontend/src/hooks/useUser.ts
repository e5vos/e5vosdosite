import { SerializedError } from "@reduxjs/toolkit";
import { FetchBaseQueryError } from "@reduxjs/toolkit/dist/query";
import { api } from "lib/api";
import { useDispatch, useSelector } from "lib/store";
import { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { authSlice } from "reducers/authReducer";

const useUser = (redirect: boolean = true, destination?: string) => {
  const navigate = useNavigate();
  const {
    data: user,
    error,
    isLoading,
    isFetching,
    refetch,
    ...rest
  } = api.useGetUserDataQuery();

  const [bToken, setBToken] = useState<boolean>(false);
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
    console.log(user, token, error);
    if (error && "status" in error && error.status === 401) {
      //if (token) dispatch(authSlice.actions.setToken("")); // BUG: DELETES TOKEN TOO EARLY
      if (redirectToLogin) navigate(redirectToLogin);
    }
    if (user && !user.e5code) {
      console.log("No e5code");
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

  /*
  // EXPERMINENTAL: SHOULD FIX EARLY TOKEN DELETION
  useEffect(() => {

    if (bToken) {
      if (isFetching || isLoading) {
        setBToken(false);
      } else {
        if (token) dispatch(authSlice.actions.setToken(""));
        setBToken(false);
      }
    }
  }, [bToken, dispatch, isFetching, isLoading, token]);
*/
  return { user, error, isLoading, isFetching, refetch, ...rest };
};

export default useUser;
