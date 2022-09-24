import ziggyroute, {Config, RouteParamsWithQueryOverload, RouteParam} from "ziggy-js"
import {Capacitor} from "@capacitor/core"

const ziggyConfig: Config = {
    url: 'http://localhost:8000',
    routes: {},
    defaults: {}
};
declare const route: (name: string, params?: RouteParamsWithQueryOverload | RouteParam) => string
const routeSwitcher = (name: string, params?: RouteParamsWithQueryOverload | RouteParam | undefined, absolute?: boolean | undefined) : string  => {
    if(Capacitor.getPlatform() === 'web') return route(name, params);
    else return ziggyroute(name, params, absolute, ziggyConfig)
}

export default routeSwitcher