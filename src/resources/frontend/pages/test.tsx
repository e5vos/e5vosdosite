import { Disclosure, Popover } from "@headlessui/react";
import useUser from "hooks/useUser";

import {
  Attendance,
  Event,
  TeamAttendance,
  UserAttendance,
  isTeamAttendance,
} from "types/models";

import { isOperator } from "lib/gates";
import { getInitials } from "lib/util";

import { gated } from "components/Gate";
import Loader from "components/UIKit/Loader";
import { Subtitle, Title } from "components/UIKit/Typography";

const TeamAttendanceShowcase = ({
  event,
  attendance,
}: {
  event: Event;
  attendance: TeamAttendance;
}) => {
  return <>WiP</>;
};

const CircleMonogram = ({
  className,
  children,
  ...rest
}: React.DetailedHTMLProps<
  React.HTMLAttributes<HTMLDivElement>,
  HTMLDivElement
>) => {
  return (
    <div
      className={`flex aspect-square h-16 w-16 resize-none items-center justify-center rounded-full bg-gray-300 ${
        className ?? ""
      }`}
      {...rest}
    >
      {children}
    </div>
  );
};

const examplenames = [
  "Kovács Béluska",
  "Nagy Elemér",
  "Teszt Elek",
  "Mikis Theodorakisz",
  "Isten Tudja",
  "Lakatos Márk",
  "Kis Pista Bácsi",
  "Hugyos Józsi",
];

const UserAttendanceShowcase = ({
  event,
  attendance,
}: {
  event: Event;
  attendance: UserAttendance;
}) => {
  return (
    <div className="mx-auto my-3 min-h-[3.5rem] max-w-4xl bg-gray-500">
      <Disclosure>
        <Disclosure.Button
          as={"div"}
          className=" h-14 w-full text-3xl hover:bg-gray-400"
        >
          <div className="flex h-full w-full flex-row items-center justify-between px-3">
            <h2 className="">12 órás foci - A faszagyerekek</h2>
            <div className="">
              10 pont <span>▼</span>
            </div>
          </div>
        </Disclosure.Button>
        <Disclosure.Panel as={"div"} className="px-3">
          <div className="my-3 flex w-full flex-row justify-between">
            <div>
              Jelenlét regisztrálva:{" "}
              <span className="font-semibold">2023.06.10 15:03:42</span>{" "}
              <span className="italic">
                Ifj. PhD. Özv. Nagy Anasztáz (Tanár) által.
              </span>
            </div>
            <div>2. helyezett</div>
          </div>
          <div>{event.description}</div>
          <hr className="my-3" />
          <div className="text-xl font-semibold uppercase  ">
            Jelenlévő csapattagok:
          </div>
          <div className="scrollbar-thin scrollbar-thumb-white scrollbar-track-gray-400  flex snap-center snap-always scroll-pe-2.5 flex-row gap-4 overflow-auto py-3 pb-5">
            {examplenames.map((member) => (
              <CircleMonogram title={member} key={member}>
                {getInitials(member)}
              </CircleMonogram>
            ))}
          </div>
        </Disclosure.Panel>
      </Disclosure>
    </div>
  );
};

const ActivityShowcaser = ({
  activities,
}: {
  activities: { event: Event; attendance: Attendance }[];
}) => {
  console.log(activities);
  if (!activities) return <Loader />;
  return (
    <>
      <Title>Kovács Béluska (10.A) aktivitása</Title>
      <Subtitle>Összesen: 10 pont</Subtitle>
      {activities.map((activity) => {
        if (isTeamAttendance(activity.attendance))
          return (
            <TeamAttendanceShowcase
              event={activity.event}
              attendance={activity.attendance}
            />
          );
        else
          return (
            <UserAttendanceShowcase
              event={activity.event}
              attendance={activity.attendance}
            />
          );
      })}
    </>
  );
};

const testdata: { event: Event; attendance: Attendance }[] = [
  {
    attendance: {
      code: "Test",
      pivot: {
        user_id: "5",
      },
    },
    event: {
      id: 1,
      name: "Test event",
      description:
        "12 órás foci maraton a 2023-es évben is megrendezésre kerül itt, ahol a csapatok 12 órán keresztül fognak játszani, és a legjobb csapat nyer. Ez a leírás egy teszt leírás, amit a teszt oldalon használunk. Az a jó benne, hogy hosszú, és sok szöveg van benne, így jól látszik, hogy miként viselkedik a szöveg, ha túl hosszú lesz. Ezért itt még van kis Voluptate sunt duis occaecat deserunt qui sit laboris nulla dolore in ad pariatur.",
      starts_at: new Date().toDateString(),
      ends_at: new Date().toDateString(),
      location_id: 1,
      location: {
        id: 1,
        name: "Test location",
      },
      is_competition: false,
      occupancy: 1,
      organiser: "Test organiser",
    },
  } as any,
];

const ThisIsTest = () => {
  return (
    <div className="m-2 mx-auto w-1/2 border-8 border-red-500 bg-red-800 p-2 text-center text-4xl font-bold text-red-50">
      Ajjajj, Teszt oldal
    </div>
  );
};

const Test = () => {
  const { data: activites } = { data: testdata };
  if (!activites) return <Loader />;
  return (
    <div>
      <ActivityShowcaser activities={activites} />
    </div>
  );
};
export default gated(
  () => (
    <>
      <ThisIsTest /> <Test />
    </>
  ),
  isOperator,
);
