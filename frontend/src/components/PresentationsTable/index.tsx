import Button from "components/UIKit/Button";
import Loader from "components/UIKit/Loader";
import useUser from "hooks/useUser";
import { is9NY } from "lib/gates";
import { Presentation } from "types/models";

const getColor = (capacity: number | null) => {
  if (capacity === null) return "bg-green-400";
  switch (true) {
    case capacity > 20:
      return "bg-green-400";
    case capacity > 15:
      return "bg-yellow-400";
    case capacity > 10:
      return "bg-yellow-600";
    case capacity > 5:
      return "bg-red-400";
    default:
      return "bg-red-500";
  }
};

const PresentationsTable = ({
  presentations,
  callback,
  disabled,
  isLoading,
}: {
  presentations: Presentation[];
  callback?: (presentation: Presentation) => void;
  disabled?: boolean;
  isLoading?: boolean;
}) => {
  const { user } = useUser(false);
  return (
    <table className="flex w-full table-auto border-separate border-spacing-y-1 border-spacing-x-0.5 flex-col text-sm md:table md:border-spacing-y-2 md:text-lg">
      <thead className="hidden border-separate bg-gray-300 text-white md:table-header-group ">
        <tr className="shadow-md">
          <th className="rounded-l-lg py-1">Előadás címe</th>
          <th className="py-1">Előadó</th>
          <th className="py-1">Előadás leírása</th>
          <th className="rounded-r-lg py-1 md:min-w-fit"></th>
        </tr>
      </thead>
      <tbody>
        {isLoading && (
          <tr>
            <td colSpan={4}>
              <Loader />
            </td>
          </tr>
        )}
        {!isLoading &&
          presentations.map((presentation, index) => (
            <tr
              key={index}
              className="mb-5 flex flex-col rounded bg-gray-400 shadow-md md:table-row md:rounded-none"
            >
              <td className="rounded-l-lg border-hidden px-2 py-0.5  text-center font-bold">
                {presentation.name}
              </td>
              <td className="px-2 py-0.5 text-center underline ">
                {presentation.organiser}
              </td>
              <td className="px-2 py-0.5 text-center ">
                {presentation.description}
              </td>
              {/*TODO: dynamically set cell color and button placement*/}
              <td
                className={
                  "m-4 whitespace-normal rounded-l-lg rounded-r-lg border-hidden  px-2 py-2 text-center text-black md:h-24 md:rounded-l-none md:py-0 " +
                  getColor(
                    presentation.capacity
                      ? presentation.capacity - presentation.occupancy
                      : null
                  )
                }
              >
                <div className="py-0.5">
                  <div>
                    {!presentation.signup_deadline ||
                    Date.parse(presentation.signup_deadline) < Date.now()
                      ? presentation.location?.name ?? "Ismeretlen hely"
                      : presentation.capacity
                      ? presentation.capacity - presentation.occupancy < 0
                        ? "Túltöltve"
                        : presentation.capacity - presentation.occupancy
                      : "Korlátlan"}
                  </div>
                  {callback &&
                  (!presentation.signup_deadline ||
                    Date.parse(presentation.signup_deadline) >= Date.now()) &&
                  (presentation.capacity > presentation.occupancy ||
                    (user && is9NY(user))) ? (
                    <div>
                      <Button
                        variant="secondary"
                        className="px-2 py-0"
                        onClick={() => callback(presentation)}
                        disabled={disabled}
                      >
                        Kiválaszt
                      </Button>
                    </div>
                  ) : (
                    <></>
                  )}
                </div>
              </td>
            </tr>
          ))}
      </tbody>
    </table>
  );
};

export default PresentationsTable;
