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

const PresentationSignup = ({
  presentations,
}: {
  presentations: Presentation[];
}) => {
  return (
    <div className="mx-auto max-w-6xl">
      <div className="min-w-full transition-colors duration-500">
        <table className="min-w-full text-sm md:text-lg border-separate border-spacing-y-1 md:border-spacing-y-2 border-spacing-x-0.5">
          <thead className="bg-[#222831] text-white border-separate ">
            <tr className="shadow-md">
              <th className="py-1 rounded-l-lg">Előadó</th>
              <th className="py-1">Előadás címe</th>
              <th className="py-1">Előadás leírása</th>
              <th className="py-1 rounded-r-lg">Szabad helyek</th>
            </tr>
          </thead>
          <tbody>
            {presentations.map((presentation) => (
              <tr className="shadow-md">
                <td className="px-2 py-0.5 text-center rounded-l-lg">
                  {presentation.organiser}
                </td>
                <td className="px-2 py-0.5 text-center">{presentation.name}</td>
                <td className="px-2 py-0.5 text-center">
                  {presentation.description}
                </td>
                {/*TODO: dynamically set cell color and button placement*/}
                <td
                  className={`px-2 text-center whitespace-normal rounded-r-lg ${getColor(
                    presentation.capacity
                  )}`}
                >
                  <div>{presentation.capacity}</div>
                  <div>
                    <button className="px-2 rounded-3xl shadow-md bg-white">
                      Kiválaszt
                    </button>
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

export default PresentationSignup;
