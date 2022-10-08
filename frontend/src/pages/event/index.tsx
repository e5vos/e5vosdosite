import EventManager from "components/Event/EventManager";
import { Event } from "types/models";

const EventsPage = ({ events }: { events: [Event] }) => {
    document.body.classList.add('bg-[#393E46]');
    return (
        <div className="mx-5 md:mx-10 lg:mx-20 my-5 md:my-10">
            <div className="flex text-md md:text-lg lg:text-2xl font-semibold text-black">
                <div>
                    <input type="radio" id="e1" name="hosting" value="e1" className="hidden peer" required></input>
                    <label htmlFor="e1" className="inline-flex justify-between items-center p-2 md:p-4 bg-[#EEEEEE] cursor-pointer peer-checked:bg-[#222831] peer-checked:text-white shadow-md ">                           
                        <div className="block">
                            <div className="w-full whitespace-nowrap">1. Előadássáv</div>
                        </div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="e2" name="hosting" value="e2" className="hidden peer"></input>
                    <label htmlFor="e2" className="inline-flex justify-between items-center p-2 md:p-4 bg-[#EEEEEE] cursor-pointer peer-checked:bg-[#222831] peer-checked:text-white shadow-md ">
                        <div className="block">
                            <div className="w-full whitespace-nowrap">2. Előadássáv</div>
                        </div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="e3" name="hosting" value="e3" className="hidden peer"></input>
                    <label htmlFor="e3" className="inline-flex justify-between items-center p-2 md:p-4 bg-[#EEEEEE] cursor-pointer peer-checked:bg-[#222831] peer-checked:text-white shadow-md ">
                        <div className="block">
                            <div className="w-full whitespace-nowrap">3. Előadássáv</div>
                        </div>
                    </label>
                </div>
            </div>

            <div className="py-3 md:py-5 lg:py-10">
                <button className="px-2 py-1 text-sm md:text-lg font-semibold border-hidden rounded-3xl shadow-md bg-[#FFD369]">+ Előadások szűrése</button>
            </div>

            <div>
                <EventManager events={events}></EventManager>
            </div>
        </div>

    );

    
};



export default EventsPage;
