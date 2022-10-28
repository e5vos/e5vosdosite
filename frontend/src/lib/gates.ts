import { User } from "types/models";

export type GateFunction<T = any> = (user: User, ...rest: T[]) => boolean;

export const isTanar = (user: User) => {
  return (
    user.permissions?.find((permission) => permission.code === "TCH") !==
    undefined
  );
};
