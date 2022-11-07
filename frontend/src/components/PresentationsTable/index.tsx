import Button from "components/UIKit/Button";
import Loader from "components/UIKit/Loader";
import { Presentation } from "types/models";

function getColor(capacity: number) {
  switch (true) {
    case capacity === null:
      return "bg-green-400";
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
}

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
  return (
    <div className="mx-auto max-w-6xl">
      <div className="min-w-full transition-colors duration-500">
        <table className="flex flex-col md:table w-full text-sm md:text-lg border-separate border-spacing-y-1 md:border-spacing-y-2 border-spacing-x-0.5">
          <thead className="hidden md:visible bg-gray-300 text-white border-separate ">
            <tr className="shadow-md">
              <th className="py-1 rounded-l-lg">Előadás címe</th>
              <th className="py-1">Előadó</th>
              <th className="py-1">Előadás leírása</th>
              <th className="py-1 rounded-r-lg">Szabad helyek</th>
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
              presentations.map((presentation) => (
                <tr
                  key={presentation.id}
                  className="flex flex-col mb-5 mx-3 md:table-row shadow-md bg-gray-400"
                >
                  <td className="px-2 py-0.5 text-center font-bold  border-hidden rounded-l-lg">
                    {presentation.name}
                  </td>
                  <td className="px-2 py-0.5 underline text-center ">
                    {presentation.organiser}
                  </td>
                  <td className="px-2 py-0.5 text-center ">
                    {presentation.description}
                  </td>
                  {/*TODO: dynamically set cell color and button placement*/}
                  <td
                    className={
                      "px-2 m-4 py-2 md:py-0 md:h-24  rounded-l-lg md:rounded-l-none text-center whitespace-normal border-hidden rounded-r-lg text-black " +
                      getColor(presentation.capacity)
                    }
                  >
                    <div className="py-0.5">
                      <div>{presentation.capacity ?? "Korlátlan"}</div>
                      {presentation.capacity !== 0 && callback ? (
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
      </div>
    </div>
  );
};

export default PresentationsTable;
