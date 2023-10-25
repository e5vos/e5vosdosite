import { SlotType } from "types/models";

import eventAPI from "lib/api/eventAPI";

const useGetPresentationSlotsQuery = () => {
    const { data, ...rest } = eventAPI.useGetSlotsQuery();

    return {
        data: data
            ?.slice()
            .filter((slot) => slot.slot_type === SlotType.Presentation),
        ...rest,
    };
};

export default useGetPresentationSlotsQuery;
