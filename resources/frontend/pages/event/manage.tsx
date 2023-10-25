import { isOperator } from "lib/gates";

import { gated } from "components/Gate";

const EventsManagePage = () => {
    return <>Event Admin</>;
};

export default gated(EventsManagePage, isOperator);
