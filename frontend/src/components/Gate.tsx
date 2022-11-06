import useUser from "hooks/useUser";
import { User } from "types/models";
import Error from "./Error";
import { ReactNode } from "react";
import Loader from "./UIKit/Loader";
import { GateFunction } from "lib/gates";

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
    return <Error code={403} message={gate.message ?? "Nincs jogosultságod"} />;
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
