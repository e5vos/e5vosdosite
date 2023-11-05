import useUser from "hooks/useUser";
import { ReactNode } from "react";

import Loader from "components/UIKit/Loader";

export const loginRequired =
    <T extends (props: any) => ReactNode>(Component: T) =>
    (props: Parameters<T>[0]) => {
        const { isLoading } = useUser();
        if (isLoading) return <Loader />;
        return <Component {...props} />;
    };
