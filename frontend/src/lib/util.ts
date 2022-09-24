export const BASE_URL = () =>
  typeof window !== "undefined"
    ? `${window.location.protocol}//${window.location.host}`
    : undefined;
export function sleep(ms: number){
    return new Promise((r) => setTimeout(r,ms))
}