import { useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";
import route from "./route";

const useUser = (redirect = true, destination?: string) => {
  const navigate = useNavigate();
  const [user] = useState();
  const [error] = useState();
  let redirectTo: any | undefined;

  if (!redirect) redirectTo = undefined;
  else if (destination)
    redirectTo = route("login", {
      _query: {
        next: destination,
      },
    });
  else
    redirectTo = route("login", {
      _query: {
        next: -2,
      },
    });

  useEffect(() => {
    if (!user && !error) {
      return;
    }
    if (redirectTo && error) {
      navigate(redirectTo);
    }
  }, [error, navigate, redirectTo, user]);

  const mutateUser: (data: any) => Promise<any> = (e) => Promise.resolve(e);
  const isLoading: boolean = false;

  return { user, mutateUser, isLoading };
};

export default useUser;
