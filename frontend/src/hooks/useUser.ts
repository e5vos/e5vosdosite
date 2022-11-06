import { SerializedError } from "@reduxjs/toolkit";
import { FetchBaseQueryError } from "@reduxjs/toolkit/dist/query";
import { api } from "lib/api";
import { useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";

const useUser = (redirect: boolean = true, destination?: string) => {
  const navigate = useNavigate();
  const { data: user, error, ...rest } = api.useGetUserDataQuery();
  const location = useLocation();

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

  useEffect(() => {
    if (!user && error && redirectToLogin) {
      navigate(redirectToLogin);
    }

    if (user && !user.e5code) {
      if (redirectToStudentCode) navigate(redirectToStudentCode);
    }
  }, [user, error, navigate, redirectToLogin, redirectToStudentCode]);

  return { user, error, ...rest };
};

export default useUser;
