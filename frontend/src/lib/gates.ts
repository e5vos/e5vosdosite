import { User } from "types/models";

export type GateFunction<T = any> = ((user: User, ...rest: T[]) => boolean) & {
  message?: string;
};

const gate = <T = any>(
  fun: (user: User, ...rest: T[]) => boolean,
  message?: string,
): GateFunction => {
  return Object.assign(
    (user: User, ...rest: T[]) => {
      if (
        user.permissions?.find((permission) => permission.code === "OPT") !==
        undefined
      )
        return true;
      return fun(user, ...rest);
    },
    { message },
  );
};

export const isTeacher = gate((user) => {
  return (
    user.permissions?.find((permission) => permission.code === "TCH") !==
    undefined
  );
}, "Csak tanárok számára elérhető");

export const isOperator = gate((user) => {
  return (
    user.permissions?.find((permission) => permission.code === "OPT") !==
    undefined
  );
}, "Csak operátorok számára elérhető");

export const isTeacherAdmin = gate((user) => {
  return (
    user.permissions?.find((permission) => permission.code === "TAD") !==
    undefined
  );
}, "Csak tanár adminok számára elérhető");
