
/**
 * Cursor pagination (cursor,nextCursor) for SWR
 *
 * @param url URL to fetch
 * @param pageSize
 * @returns SWRInfiniteKeyLoader for useSWRInfinite hook
 */
 export function paginatedLoader(
    url: URL | string | null,
    pageSize: number = 10
  ): SWRInfiniteKeyLoader {
    return (_pageIndex, previousPageData) => {
      if (url === null) return null;
      else if (url instanceof URL) {
        if (!previousPageData) {
          url.searchParams.set("cursor", "0");
        } else if (!previousPageData.data.length) {
          return null;
        } else {
          const nextCursor = String(previousPageData.nextCursor ?? 0);
  
          url.searchParams.set("cursor", nextCursor);
        }
  
        url.searchParams.set("limit", String(pageSize));
        return url.toString();
      } else if (typeof url === "string") {
        if (!previousPageData) {
          return url + "?cursor=0&limit=" + pageSize;
        } else if (!previousPageData.data.length) {
          return null;
        } else {
          const nextCursor = String(previousPageData.nextCursor ?? 0);
  
          return url + "?cursor=" + nextCursor + "&limit=" + pageSize;
        }
      }
    };
  }