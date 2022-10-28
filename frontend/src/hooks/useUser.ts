import { SerializedError } from "@reduxjs/toolkit";
import { FetchBaseQueryError } from "@reduxjs/toolkit/dist/query";
import { api } from "lib/api";
import { useLocation, useNavigate } from "react-router-dom";
import {User} from "types/models";

const useUser = (redirect: boolean = true, destination?: string) : {user?: User, error?: FetchBaseQueryError | SerializedError | Error} => {
    const navigate = useNavigate()
    const {data: user, error} = api.useGetUserDataQuery();
    const location = useLocation()


    let redirectTo: string | undefined;
    if (!redirect) redirectTo = undefined;
    else if (destination) redirectTo = destination;
    else redirectTo = "/login?next=" + location.pathname;

    if(error && redirectTo){
        navigate(redirectTo)
    }

    if(user && !user.code){
        return {user, error: Error("No Student Code")}
    }

    return {user,error}    
    
}

export default useUser;