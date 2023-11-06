import useUser from "hooks/useUser";
import { ReactNode } from "react";

import Loader from "components/UIKit/Loader";

export const loginRequired =
    <T extends (props: any) => ReactNode>(Component: T) =>
    (props: any) => {
        // TODO: FIX typing
        const { isLoading } = useUser();
        if (isLoading) return <Loader />;
        return <Component {...props} />;
    };
