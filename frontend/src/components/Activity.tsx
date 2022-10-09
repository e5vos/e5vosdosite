import { Disclosure, Transition } from "@headlessui/react";
import { Attendance, isTeamAttendance } from "types/models"

const Activity = ({
  name,
  attendance,
}: {
  name: string;
  attendance: Attendance;
}) => {
  const Members = ({ attendance }: { attendance: Attendance }) => {
    if (isTeamAttendance(attendance))
      return (
        <>
          <hr className="bg-gray-400 rounded h-1" />
          <h4 className="text-center">Jelenlévők</h4>
          <ul className="mx-5 border-2 rounded border-red-500 flex flex-row flex-wrap">
            {attendance.users.map((user) => (
              <li className="mr-2 border-red-300 border-4 rounded-lg m-3">
                {user.last_name} {user.first_name} - {user.class}
              </li>
            ))}
          </ul>
        </>
      );
    else return <></>;
  };

  return (
    <Disclosure
      as="div"
      className="w-full mx-auto max-w-md rounded-2xl bg-white p-2 mb-3"
    >
      <Disclosure.Button className="flex w-full justify-between bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200">
        <div>{name}</div>
        <div>
          {attendance.place && (
            <>
              <span className="font-bold">{attendance.place}.hely</span> -
            </>
          )}
          {attendance.point} pont
        </div>
      </Disclosure.Button>
      <Transition
        enter="transition duration-100 ease-out"
        enterFrom="transform scale-95 opacity-0"
        enterTo="transform scale-100 opacity-100"
        leave="transition duration-75 ease-out"
        leaveFrom="transform scale-100 opacity-100"
        leaveTo="transform scale-95 opacity-0"
      >
        <Disclosure.Panel className="w-100 border-4 border-black rounded m-2 p-2 text-left px-4">
          <table className="w-full w-100">
            <tbody>
              <tr>
                <td className="text-left">Jelentkezés regisztrálva:</td>
                <td className="text-right">{attendance.created_at}</td>
              </tr>
              <tr>
                <td className="text-left">Jelenlét regisztrálva:</td>
                <td className="text-right">{attendance.scan_at}</td>
              </tr>
            </tbody>
          </table>
          <Members attendance={attendance} />
        </Disclosure.Panel>
      </Transition>
    </Disclosure>
  );
};
export default Activity;
