import { User } from "types/models";
import { number } from "yup";

export const BASE_URL = () =>
  typeof window !== "undefined"
    ? `${window.location.protocol}//${window.location.host}`
    : undefined;
export function sleep(ms: number) {
  return new Promise((r) => setTimeout(r, ms));
}

export const sortByEJGClass = (a: User, b: User) => {
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
