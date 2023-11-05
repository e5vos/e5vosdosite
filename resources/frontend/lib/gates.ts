import { Event, PermissionCode, User } from "types/models";

export type GateFunction = ((user: User | undefined) => boolean) & {
    message?: string;
};

const gate = (fun: (user: User) => boolean, message?: string): GateFunction => {
    return Object.assign(
        (user: User | undefined) => {
            if (!user) return false;
            if (
                user.permissions?.find(
                    (permission) => permission.code === PermissionCode.operator,
                ) !== undefined
            )
                return true;
            return fun(user);
        },
        { message },
    );
};

export const isTeacher = gate((user) => {
    return (
        user.permissions?.find(
            (permission) => permission.code === PermissionCode.teacher,
        ) !== undefined
    );
}, "Csak tanárok számára elérhető");

export const isOperator = gate((user) => {
    return (
        user.permissions?.find(
            (permission) => permission.code === PermissionCode.operator,
        ) !== undefined
    );
}, "Csak operátorok számára elérhető");

export const isAdmin = gate((user) => {
    return (
        user.permissions?.find(
            (permission) => permission.code === PermissionCode.admin,
        ) !== undefined
    );
}, "Csak adminok számára elérhető");

export const isTeacherAdmin = gate((user) => {
    return (
        user.permissions?.find(
            (permission) => permission.code === PermissionCode.teacheradmin,
        ) !== undefined
    );
}, "Csak tanár adminok számára elérhető");

export const isScanner = (event: Event) =>
    gate((user) => {
        return (
            user.permissions?.find(
                (permission) =>
                    permission.code === "SCN" &&
                    permission.event_id === event.id,
            ) !== undefined
        );
    }, "Csak scannerek számára elérhető");

export const isOrganiser = (event: Event) =>
    gate((user) => {
        return (
            user.permissions?.find(
                (permission) =>
                    permission.code === "ORG" &&
                    permission.event_id === event.id,
            ) !== undefined
        );
    }, "Csak szervezők számára elérhető");

export const userGate = gate(() => true, "Csak felhasználók számára érhető el");
