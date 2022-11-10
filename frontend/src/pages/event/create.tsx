import { gated } from "components/Gate";
import { isOperator } from "lib/gates";

const CreateEventPage = () => {
  return <></>;
};

export default gated(CreateEventPage, isOperator);
