import { useMemo } from "react";

import { api } from "lib/api";

const useGetPresentationSlotsQuery = () => {
  const { data, ...rest } = api.useGetSlotsQuery();

  return {
    data: data?.slice().filter((slot) => slot.slot_type === "Előadássáv"),
    ...rest,
  };
};

export default useGetPresentationSlotsQuery;
