import { isOperator } from "lib/gates";

import { gated } from "components/Gate";

const CreateEventPage = () => {
    return <></>;
};

export default gated(CreateEventPage, isOperator);
