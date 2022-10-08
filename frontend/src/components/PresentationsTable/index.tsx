import Button from "components/UIKit/Button";
import { Presentation } from "types/models";

function getColor(capacity: number) {
  switch (true) {
    case capacity > 25:
      return "bg-[#43B547]";
    case capacity > 20:
      return "bg-[#7BBB47]";
    case capacity > 15:
      return "bg-[#AAC847]";
    case capacity > 10:
      return "bg-[#BCBB47]";
    case capacity > 5:
      return "bg-[#BCA447]";
    case capacity > 0:
      return "bg-[#BC8547]";
    default:
      return "bg-[#BC4647]";
  }
}

const PresentationsTable = ({
  presentations,
  callback
}: {
  presentations: Presentation[];
  callback?: (presentation: Presentation) => void;
}) => {
  return (
    <div className="mx-auto max-w-6xl">
      <div className="min-w-full transition-colors duration-500">
        <table className="min-w-full text-sm md:text-lg border-separate border-spacing-y-1 md:border-spacing-y-2 border-spacing-x-0.5">
          <thead className="bg-[#222831] text-white border-separate ">
            <tr className="shadow-md">
              <th className="w-[20%] py-1 rounded-l-lg">Előadó</th>
              <th className="w-[25%] py-1">Előadás címe</th>
              <th className="w-[35%] py-1">Előadás leírása</th>
              <th className="w-[20%] py-1 rounded-r-lg">Szabad helyek</th>
            </tr>
          </thead>
          <tbody>
            {presentations.map((presentation) => (
              <tr className="shadow-md">
              <td className="px-2 py-0.5 text-center  border-hidden rounded-l-lg">{presentation.organiser}</td>
              <td className="px-2 py-0.5 text-center ">{presentation.name}</td>
              <td className="px-2 py-0.5 text-center ">{presentation.description}</td>
              {/*TODO: dynamically set cell color and button placement*/}
              <td className={"px-2 text-center whitespace-normal border-hidden rounded-r-lg " + getColor(presentation.capacity)}>
                  <div className="py-0.5">
                      <div>{presentation.capacity}</div>
                      {presentation.capacity !== 0 && callback ? <div><Button variant="secondary" className="px-2 py-0" onClick={() => callback(presentation)}>Kiválaszt</Button></div> :  <></>}
                      
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
