import axios, {AxiosError} from "axios";
import swrOriginal, { Key, mutate, SWRConfiguration } from "swr";
import swrInfiniteOriginal, {
  SWRInfiniteConfiguration,
  SWRInfiniteKeyLoader,
} from "swr/infinite";

const defaultOptions: SWRConfiguration = {
    
}

const defaultInfiniteOptions : SWRInfiniteConfiguration = {

}

const routeOptions: Map<Key, SWRConfiguration> = new Map([]);

export const fetcher =  (key: string) => {
    if(key.match(".*undefined.*")) return Promise.reject("Invalid key")
    else return axios.get(key).then((res) => res.data).catch((error) => {
        if(error instanceof AxiosError){
            throw error;
        }else throw error;
    });
}


/**
 * useSWR wrapper
 * @param url
 * @param options
 * @returns
 */
 export const useSWR = <T>(key: Key | null, options?: SWRConfiguration) => {
    const currentRouteOptions = routeOptions.get(key) ?? {};
    return swrOriginal<T>(key, fetcher, {
      ...defaultOptions,
      ...currentRouteOptions,
      ...options,
    });
  };
  /**
   * useSWRInfinite wrapper
   * @param keyLoader
   * @param options
   * @returns
   */
  export const useSWRInfinite = <T>(
    keyLoader: SWRInfiniteKeyLoader,
    options?: SWRInfiniteConfiguration
  ) =>
    swrInfiniteOriginal<{ data: T; nextCursor: number }>(keyLoader, fetcher, {
      ...defaultInfiniteOptions,
      ...options,
    });
  
  /**
   * Prefetcher for SWR
   * @param url
   * @returns
   */
  export const prefetch = (url: string) => {
    return mutate(url, fetcher(url));
  };
  
  export default useSWR;
