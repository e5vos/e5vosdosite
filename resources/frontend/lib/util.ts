import { User, UserStub } from "types/models";

export const BASE_URL = () =>
    typeof window !== "undefined"
        ? `${window.location.protocol}//${window.location.host}`
        : undefined;
export function sleep(ms: number) {
    return new Promise((r) => setTimeout(r, ms));
}

export const sortByEJGClass = (a: UserStub, b: UserStub) => {
    if (a.ejg_class && b.ejg_class) {
        const [AYear, Aclass] = a.ejg_class.split(".");
        const [BYear, Bclass] = b.ejg_class.split(".");
        if (Aclass && Bclass) {
            if (AYear === BYear) {
                if (Aclass === Bclass) {
                    return a.name.localeCompare(b.name);
                } else {
                    return Aclass.localeCompare(Bclass);
                }
            } else {
                const AYearNum = parseInt(AYear);
                const BYearNum = parseInt(BYear);
                return AYearNum - BYearNum;
            }
        }
        return a.ejg_class.localeCompare(b.ejg_class);
    }
    if (a.ejg_class || b.ejg_class) return 1;
    return a.name.localeCompare(b.name);
};

export const reverseNameOrder = (s: string) => {
    const names = s.split(" ");
    return [names.at(-1), ...names.slice(0, -1)].join(" ");
};

export const getInitials = (s: string) => {
    const names = s.split(" ");
    return names.map((name) => name[0]).join("");
};

export const formatDateInput = (date: Date) => {
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();

    const monthStr = month < 10 ? `0${month}` : month;
    const dayStr = day < 10 ? `0${day}` : day;

    return `${year}-${monthStr}-${dayStr}`;
};
