import { ReactComponent as Donci } from "assets/donci.svg";
const Loader = ({ className }: { className?: string }) => {
  return (
    <div
      className={`text-bold mx-auto my-auto text-center text-5xl ${
        className ?? ""
      }`}
    >
      <div className="min-h-[125px]">
        <Donci className="mx-auto max-h-[100px] animate-spin fill-gray-200 hover:fill-gray-50" />
      </div>
    </div>
  );
};
export default Loader;
