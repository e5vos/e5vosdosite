import { api } from "lib/api";
import { useMemo } from "react";
const useGetPresentationSlotsQuery = () => {
  const { data, ...rest } = api.useGetSlotsQuery();

  return {
    data: data?.slice().filter((slot) => slot.slot_type === "Előadássáv"),
    ...rest,
  };
};

export default useGetPresentationSlotsQuery;
