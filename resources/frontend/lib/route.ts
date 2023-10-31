import ziggyroute, { Config, RouteName, RouteParams } from "ziggy-js";

import includedZiggy from "./includeZiggy";

declare global {
    interface Window {
        Ziggy: any;
        route:
            | ((name: string, params?: RouteParams<string>) => string)
            | undefined;
    }
}

let remoteZiggyConfig: Config | undefined = undefined;

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(includedZiggy.routes, window.Ziggy.routes);
} else if (import.meta.env.VITE_USE_REMOTE_ZIGGY) {
    fetch(`${import.meta.env.VITE_BACKEND}/api/ziggy`, {
        credentials: "include",
    })
        .then((res) => res.json())
        .then((res) => {
            remoteZiggyConfig = res as Config;
        })
        .catch((error) => {
            console.error(error);
        });
}

declare global {
    interface Window {}
}

const routeSwitcher = <T extends RouteName>(
    name: T,
    params?: RouteParams<T> | undefined,
    absolute?: boolean | undefined,
): string => {
    try {
        return ziggyroute(
            name,
            params,
            absolute,
            remoteZiggyConfig ?? includedZiggy,
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
