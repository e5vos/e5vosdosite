import useUser from 'hooks/useUser'
import { useMemo } from 'react'
import { useNavigate } from 'react-router-dom'

import { Event } from 'types/models'

import { isAdmin, isTeacherAdmin } from 'lib/gates'
import Locale from 'lib/locale'

import Button from './UIKit/Button'
import ButtonGroup from './UIKit/ButtonGroup'

const locale = Locale({
    hu: 'Esemény megtekintése',
    en: 'View event',
})

const EventCard = ({
    event,
    className,
}: {
    event: Event
    className?: string
}) => {
    const user = useUser(false)
    const navigate = useNavigate()
    const isFootball = useMemo(() => event.slot?.name === 'Foci', [event.slot])
    return (
        <div
            className={`mb-3 flex flex-col justify-between rounded-lg bg-gray-600 p-2 ${
                className ?? ''
            }`}
        >
            <div>
                <h3 className="px-2 text-2xl font-bold">{event.name}</h3>
                <h4 className="px-2 text-sm">{event.organiser}</h4>
                {isFootball ? (
                    <div className="flex w-full items-center justify-center gap-2 py-2">
                        <div className="rounded-lg bg-slate-100 px-6 py-2 text-xl font-semibold text-gray-700">
                            {event.description.substring(
                                0,
                                event.description.indexOf('-')
                            )}
                        </div>
                        -
                        <div className="rounded-lg bg-slate-100 px-6 py-2 text-xl font-semibold text-gray-700">
                            {event.description.substring(
                                event.description.indexOf('-') + 1
                            )}
                        </div>
                    </div>
                ) : (
                    <p>{event.description}</p>
                )}
            </div>
            <div>
                <div className="flew-row mb-1 mt-2 flex w-full justify-between">
                    <div className="rounded-full bg-gray-400 px-3">
                        {event.location?.name ?? 'Ismeretlen'}
                    </div>
                    {(isAdmin(user) || isTeacherAdmin(user)) && (
                        <div className=" flex overflow-hidden rounded-full bg-slate-200">
                            <p className="bg-red-400 pl-3 pr-2">
                                {event.slot_id ?? '-'}
                            </p>
                            <p className="bg-blue-400 pl-2 pr-3">{event.id}</p>
                        </div>
                    )}
                </div>
                <ButtonGroup className="mt-1 w-full">
                    <Button
                        variant="info"
                        onClick={() => navigate(`/esemeny/${event.id}`)}
                        className="text-white"
                    >
                        {locale}
                    </Button>
                </ButtonGroup>
            </div>
        </div>
    )
}

export default EventCard
