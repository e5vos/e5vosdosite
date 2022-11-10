import { gated } from "components/Gate";
import { isOperator } from "lib/gates";

const EventsManagePage = () => {
  return <>Event Admin</>;
};

export default gated(EventsManagePage, isOperator);
