import useUser from 'hooks/useUser'
import { useState } from 'react'
import { Link } from 'react-router-dom'

import eventAPI from 'lib/api/eventAPI'
import { isAdmin } from 'lib/gates'
import Locale from 'lib/locale'

import Error from 'components/Error'
import EventCard from 'components/EventCard'
import Button from 'components/UIKit/Button'
import ButtonGroup from 'components/UIKit/ButtonGroup'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        title: `${import.meta.env.VITE_EVENT_HU_SHORT} - Programok`,
        create: 'Program hozzadása',
    },
    en: {
        title: `${import.meta.env.VITE_EVENT_EN_SHORT} - Events`,
        create: 'Create event',
    },
})

const ViewSlotEventsPage = () => {
    const { data: slots, error: slotsError } = eventAPI.useGetSlotsQuery()
    const { user } = useUser(false)
    const [currentSlot, setCurrentSlot] = useState(0)

    const { data: events, isFetching: isEventsFetching } =
        eventAPI.useGetEventsQuery(
            slots ? (slots[currentSlot] ?? { id: -1 }) : { id: -1 }
        )

    if (slotsError) return <Error code={500} />
    if (!slots) return <Loader />

    return (
        <div className="mx-5">
            <div className="container mx-auto">
                <h1 className="max-w-f pb-4 text-center text-4xl font-bold">
                    {locale.title}{' '}
                    {isAdmin(user) && (
                        <Link
                            to="/esemeny/uj"
                            className="my-auto align-middle text-sm"
                        >
                            <Button variant="primary">{locale.create}</Button>
                        </Link>
                    )}
                </h1>

                <div className="mx-auto mt-4 mb-4 md:flex">
                    <ButtonGroup className="mx-auto">
                        {slots.map((slot, index) => (
                            <Button
                                variant="secondary"
                                key={slot.name}
                                disabled={index === currentSlot}
                                onClick={() => setCurrentSlot(index)}
                            >
                                {slot.name}
                            </Button>
                        ))}
                    </ButtonGroup>
                </div>
            </div>

            {isEventsFetching ? (
                <Loader />
            ) : (
                <div className="grid-cols-4 gap-2 md:grid">
                    {events?.map((event) => (
                        <EventCard event={event} key={event.id} />
                    ))}
                </div>
            )}
        </div>
    )
}

export default ViewSlotEventsPage
