import {useHistory} from "react-router-dom"
import {useEffect} from "react"
const useUser = (redirect = true, destination?: string) => {
    const history = useHistory();

    let redirectTo: string | undefined;
    if(!redirect) redirectTo = undefined
    else if(destination) redirectTo = destination;
    else redirectTo = route().login + history.asPath;

    useEffect(() => {
        if(!user && !error){
            return;
        }
        if(redirectTo && error){
            history.push(redirectTo)
        }
    },[])
    return {user, mutateUser, isLoading}
}


export default useUser;