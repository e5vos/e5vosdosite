import useUser from "hooks/useUser";
import { ReactNode } from "react";

import { User } from "types/models";

import { GateFunction } from "lib/gates";

import Error from "./Error";
import Loader from "./UIKit/Loader";

const Gate = ({
    children,
    gate,
    params,
    user: paramUser,
}: {
    children: ReactNode;
    gate: GateFunction;
    user?: User;
    params?: any[];
}) => {
    const { user, isLoading } = useUser();
    const usedUser = paramUser ?? user;
    if (isLoading) return <Loader />;
    if (!usedUser) return <Error code={401} />;
    if (params ? !gate(usedUser, ...params) : !gate(usedUser))
        return (
            <Error code={403} message={gate.message ?? "Nincs jogosultságod"} />
        );
    return <>{children}</>;
};

export default Gate;

export const gated = (Component: React.FC, gate: GateFunction) => {
    return () => (
        <Gate gate={gate}>
            <Component />
        </Gate>
    );
};
