import { Capacitor } from "@capacitor/core";
import ziggyroute, {
  Config,
  RouteParams,
} from "ziggy-js";

import includedZiggy from "./ziggy.json";

declare global {
  interface Window {
    Ziggy: any;
    route:
      | ((
          name: string,
          params?: RouteParams<string>,
        ) => string)
      | undefined;
  }
}

if (import.meta.env.DEV) {
  includedZiggy.url = import.meta.env.VITE_BACKEND;
  console.log("Ziggy url overridden for development");
}

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  Object.assign(includedZiggy.routes, window.Ziggy.routes);
}

let remoteZiggyConfig: Config | undefined = undefined;

fetch(`${import.meta.env.VITE_BACKEND}/api/ziggy`, { credentials: "include" })
  .then((res) => res.json())
  .then((res) => {
    remoteZiggyConfig = res;
  })
  .catch((error) => console.error(error));
declare global {
  interface Window {}
}

const routeSwitcher = (
  name: keyof typeof includedZiggy.routes | string,
  params?: RouteParams<string> | undefined,
  absolute?: boolean | undefined,
): string => {
  try {
    if (Capacitor.getPlatform() === "web" && window.route)
      return window.route(name, params);
    else
      return ziggyroute(
        name,
        params,
        absolute,
        remoteZiggyConfig ?? (includedZiggy as Config),
      );
  } catch (error) {
    if (process.env.NODE_ENV === "development") {
      console.error("PROD FATAL ERROR ", error);
      return "api/undefined";
    } else throw error;
  }
};

declare global {
  interface Window {
    consoleZiggy: typeof routeSwitcher;
  }
}

if (window !== undefined) {
  window.consoleZiggy = routeSwitcher;
}

export default routeSwitcher;
