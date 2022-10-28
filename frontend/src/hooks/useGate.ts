import { User } from "types/models";

const useGate = <T = any>(
  user: User | undefined,
  gate: (user: User, ...rest: T[]) => boolean,
  ...rest: T[]
): boolean => {
  return user ? gate(user, ...rest) : false;
};

export default useGate;
