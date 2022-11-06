import { GateFunction } from "lib/gates";
import { User } from "types/models";

const useGate = <T = any>(
  user: User | undefined,
  gate: GateFunction,
  ...rest: T[]
): boolean | string => {
  if (!user) return false;
  if (!gate(user, ...rest)) return gate.message ?? false;
  return true;
};

export default useGate;
