import { Event } from "types/models";

const EventManager = ({ events }: { events: [Event] }) => {
    return (
        <div className="flex justify-center">
            <div className="flex-1 max-w-5xl p-1.5">
                <div className="overflow-hidden min-w-full transition-colors duration-500">
                    <table className="min-w-full divide-y text-md border-separate table-fixed">
                        <thead className="bg-[#222831] text-white border-separate">
                            <tr className="h-10 shadow">
                                <th className="w-[20%] py-1 border-hidden rounded-l-lg">Előadó</th>
                                <th className="w-[25%] py-1">Előadás címe</th>
                                <th className="w-[35%] py-1">Előadás leírása</th>
                                <th className="w-[20%] py-1 border-hidden rounded-r-lg">Szabad helyek</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y bg-white">
                            {events.map((event) => (
                                //couldnt find a better way to space table rows other than inserting empty rows ;(
                                <><tr className="h-2">
                                </tr>

                                <tr className="shadow">
                                    <td className="px-2 py-2 text-center whitespace-nowrap border-hidden rounded-l-lg">{event.presenter}</td>
                                    <td className="px-2 py-2 text-center whitespace-normal">{event.name}</td>
                                    <td className="px-2 py-2 text-center whitespace-normal">{event.description}</td>
                                    {/*TODO: dynamically set cell color and button placement*/}
                                    <td className="px-2 py-2 text-center whitespace-nowrap border-hidden rounded-r-lg bg-orange-400">
                                        <div className="py-0.5">
                                            <div>{event.spaces}</div>
                                            <div><button className="px-2 border-hidden rounded-3xl shadow bg-white">Kiválaszt</button></div>
                                        </div>
                                    </td>
                                </tr></>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}

export default EventManager;