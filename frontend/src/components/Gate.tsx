import useUser from "hooks/useUser";
import { User } from "types/models";
import Error from "./Error";

const Gate = <T = any,>({
  children,
  gate,
  params,
  user: paramUser,
}: {
  children: React.ReactNode;
  gate: (user: User, ...rest: T[]) => boolean;
  user?: User;
  params: T[];
}) => {
  const { user } = useUser();
  const usedUser = paramUser ?? user;
  if (!usedUser) return <Error code={401} />;
  if (!gate(usedUser, ...params)) return <Error code={403} />;
  else return children;
};

export default Gate;
