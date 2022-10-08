import ziggyroute, {
  Config,
  RouteParamsWithQueryOverload,
  RouteParam,
} from "ziggy-js";
import { Capacitor } from "@capacitor/core";
import axios from "axios";

let remoteZiggyConfig: Config | undefined = undefined;

axios
  .get("api/ziggy")
  .then((response) => {
    remoteZiggyConfig = response.data;
  })
  .catch((error) => console.error(error));

const ziggyConfig: Config = {
  url: "http://localhost:8000",
  routes: {},
  defaults: {},
};

declare global {
  interface Window {
    route:
      | ((
          name: string,
          params?: RouteParamsWithQueryOverload | RouteParam
        ) => string)
      | undefined;
  }
}

const routeSwitcher = (
  name: string,
  params?: RouteParamsWithQueryOverload | RouteParam | undefined,
  absolute?: boolean | undefined
): string => {
  try {
    if (Capacitor.getPlatform() === "web" && window.route)
      return window.route(name, params);
    else
      return ziggyroute(
        name,
        params,
        absolute,
        remoteZiggyConfig ?? ziggyConfig
      );
  } catch (error) {
    if (process.env.NODE_ENV === "development") {
      console.error("PROD FATAL ERROR ", error);
      return "api/undefined";
    } else throw error;
  }
};

export default routeSwitcher;
