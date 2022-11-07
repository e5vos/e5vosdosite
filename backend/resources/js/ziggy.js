const Ziggy = {
    url: "http://e5vosdo.hu",
    port: null,
    defaults: {},
    routes: {
        "debugbar.openhandler": {
            uri: "_debugbar/open",
            methods: ["GET", "HEAD"],
        },
        "debugbar.clockwork": {
            uri: "_debugbar/clockwork/{id}",
            methods: ["GET", "HEAD"],
        },
        "debugbar.assets.css": {
            uri: "_debugbar/assets/stylesheets",
            methods: ["GET", "HEAD"],
        },
        "debugbar.assets.js": {
            uri: "_debugbar/assets/javascript",
            methods: ["GET", "HEAD"],
        },
        "debugbar.cache.delete": {
            uri: "_debugbar/cache/{key}/{tags?}",
            methods: ["DELETE"],
        },
        "sanctum.csrf-cookie": {
            uri: "sanctum/csrf-cookie",
            methods: ["GET", "HEAD"],
        },
        "ignition.healthCheck": {
            uri: "_ignition/health-check",
            methods: ["GET", "HEAD"],
        },
        "ignition.executeSolution": {
            uri: "_ignition/execute-solution",
            methods: ["POST"],
        },
        "ignition.updateConfig": {
            uri: "_ignition/update-config",
            methods: ["POST"],
        },
        user: { uri: "api/user", methods: ["GET", "HEAD"] },
        login: { uri: "api/login", methods: ["GET", "HEAD"] },
        "user.e5code": { uri: "api/e5code", methods: ["PATCH"] },
        "slot.index": { uri: "api/slot", methods: ["GET", "HEAD"] },
        "slot.store": { uri: "api/slot", methods: ["POST"] },
        "slot.destroy": { uri: "api/slot/{slot}", methods: ["DELETE"] },
        "slot.update": { uri: "api/slot/{slot}", methods: ["PUT"] },
        "events.index": { uri: "api/events", methods: ["GET", "HEAD"] },
        "event.store": { uri: "api/events", methods: ["POST"] },
        "events.slot": {
            uri: "api/events/{slot_id}",
            methods: ["GET", "HEAD"],
        },
        "events.presentations": {
            uri: "api/presentations",
            methods: ["GET", "HEAD"],
        },
        "events.mypresentations": {
            uri: "api/mypresentations",
            methods: ["GET", "HEAD"],
        },
        "event.show": { uri: "api/event/{eventId}", methods: ["GET", "HEAD"] },
        "event.orgas": {
            uri: "api/event/{eventId}/orgas",
            methods: ["GET", "HEAD"],
        },
        "event.update": { uri: "api/event/{eventId}", methods: ["PUT"] },
        "event.delete": { uri: "api/event/{eventId}", methods: ["DELETE"] },
        "event.restore": {
            uri: "api/event/{eventId}/restore",
            methods: ["PUT"],
        },
        "event.close_signup": {
            uri: "api/event/{eventId}/close",
            methods: ["PUT"],
        },
        "event.participants": {
            uri: "api/event/{eventId}/participants",
            methods: ["GET", "HEAD"],
        },
        "event.signup": {
            uri: "api/event/{eventId}/signup",
            methods: ["POST"],
        },
        "event.attend": {
            uri: "api/event/{eventId}/attend",
            methods: ["POST"],
        },
        "teams.index": { uri: "api/team", methods: ["GET", "HEAD"] },
        "team.store": { uri: "api/team", methods: ["POST"] },
        "team.show": { uri: "api/team/{teamCode}", methods: ["GET", "HEAD"] },
        "team.destroy": { uri: "api/team/{teamCode}", methods: ["DELETE"] },
        "team.update": { uri: "api/team/{teamCode}", methods: ["PUT"] },
        "team.restore": {
            uri: "api/team/{teamCode}/restore",
            methods: ["PUT"],
        },
        "team.invite": {
            uri: "api/team/{teamCode}/members",
            methods: ["POST"],
        },
        "team.kick": {
            uri: "api/team/{teamCode}/members",
            methods: ["DELETE"],
        },
        "team.promote": {
            uri: "api/team/{teamCode}/members",
            methods: ["PUT"],
        },
        "permission.add_organiser": {
            uri: "api/permissions/{userId}/event/{eventId}",
            methods: ["POST"],
        },
        "permission.remove_organiser": {
            uri: "api/permissions/{userId}/event/{eventId}",
            methods: ["DELETE"],
        },
        "setting.index": { uri: "api/setting", methods: ["GET", "HEAD"] },
        "setting.create": { uri: "api/setting", methods: ["POST"] },
        "setting.set": { uri: "api/setting/{key}/{value}", methods: ["PUT"] },
        "setting.destroy": { uri: "api/setting/{key}", methods: ["DELETE"] },
    },
};

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };