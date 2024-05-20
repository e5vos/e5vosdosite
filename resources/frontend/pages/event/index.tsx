import useUser from 'hooks/useUser'
import { useMemo, useState } from 'react'
import { FaSearch } from 'react-icons/fa'
import { Link } from 'react-router-dom'

import eventAPI from 'lib/api/eventAPI'
import { isAdmin } from 'lib/gates'
import Locale from 'lib/locale'

import Error from 'components/Error'
import EventCard from 'components/EventCard'
import Button from 'components/UIKit/Button'
import ButtonGroup from 'components/UIKit/ButtonGroup'
import Form from 'components/UIKit/Form'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        title: `${import.meta.env.VITE_EVENT_HU_SHORT} - Programok`,
        create: 'Program hozzadása',
        search: 'Keresés',
    },
    en: {
        title: `${import.meta.env.VITE_EVENT_EN_SHORT} - Events`,
        create: 'Create event',
        search: 'Search',
    },
})

const EventsPage = () => {
    const { data: slots, error: slotsError } = eventAPI.useGetSlotsQuery()
    const { user } = useUser(false)
    const [currentSlot, setCurrentSlot] = useState(0)

    const { data: events, isFetching: isEventsFetching } =
        eventAPI.useGetEventsQuery(
            slots ? slots[currentSlot] ?? { id: -1 } : { id: -1 }
        )

    const [search, setSearch] = useState('')

    const filteredEvents = useMemo(() => {
        if (!events) return []

        return events.filter((event) => {
            return event.name.toLowerCase().includes(search.toLowerCase())
        })
    }, [events, search])

    const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setSearch(e.target.value)
    }

    if (slotsError) return <Error code={500} />
    if (!slots) return <Loader />
    if (!events) return <Loader />
    return (
        <div className="mx-5">
            <div className="container mx-auto">
                <h1 className="max-w-f pb-4 text-center text-4xl font-bold">
                    {locale.title}
                </h1>
            </div>
            <div className="mx-auto mb-2 mt-4 flex w-full gap-2 text-center">
                <Form.Group className="!mb-0 flex w-full gap-2 rounded-lg bg-gray-600 pl-4">
                    <Form.Label className=" flex items-center gap-2 no-underline">
                        <FaSearch />
                        {locale.search}
                    </Form.Label>
                    <Form.Control
                        className="!bg-gray !mb-0 !w-full rounded-r-md !border-0"
                        onChange={handleSearchChange}
                    />
                </Form.Group>
                {isAdmin(user) && (
                    <Link
                        to="/esemeny/uj"
                        className="!m-0 my-auto !p-0 align-middle text-sm"
                    >
                        <Button
                            className="!rounded-lg !outline-none"
                            variant="primary"
                        >
                            {locale.create}
                        </Button>
                    </Link>
                )}
            </div>
            <div className="mx-auto mb-4 w-full md:flex">
                <ButtonGroup className="mx-auto w-full !rounded-lg !outline-none">
                    {slots.map((slot, index) => (
                        <Button
                            variant="secondary"
                            className="!outline-none"
                            key={slot.name}
                            disabled={index === currentSlot}
                            onClick={() => setCurrentSlot(index)}
                        >
                            {slot.name}
                        </Button>
                    ))}
                </ButtonGroup>
            </div>

            {isEventsFetching ? (
                <Loader />
            ) : (
                <div className="grid-cols-4 gap-2 md:grid">
                    {filteredEvents.map((event) => (
                        <EventCard event={event} key={event.id} />
                    ))}
                </div>
            )}
        </div>
    )
}

export default EventsPage
