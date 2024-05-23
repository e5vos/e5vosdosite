import useUser from 'hooks/useUser'
import { useMemo } from 'react'
import { useNavigate } from 'react-router-dom'

import { Event, User } from 'types/models'

import { isAdmin, isTeacherAdmin } from 'lib/gates'
import Locale from 'lib/locale'

import Button from './UIKit/Button'
import ButtonGroup from './UIKit/ButtonGroup'

const locale = Locale({
    hu: {
        view: 'Esemény megtekintése',
        football: {
            acsop: 'A csoport',
            bcsop: 'B csoport',
            ccsop: 'C csoport',
            dcsop: 'D csoport',
            negyed: 'Negyeddöntő',
            elodont: 'Elődöntő',
            donto: 'Döntő',
            bronz: 'Bronzmeccs',
            kisb: 'Kis Bajnokság',
        },
    },
    en: {
        view: 'View event',
        football: {
            acsop: 'A group',
            bcsop: 'B group',
            ccsop: 'C group',
            dcsop: 'D group',
            negyed: 'Quarterfinal',
            elodont: 'Semifinal',
            donto: 'Final',
            bronz: 'Bronze match',
            kisb: 'Small Championship',
        },
    },
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
    const footballScore = useMemo(() => {
        if (!isFootball) return [0, 0]
        return event.description.split('/')[0].split('-').map(Number)
    }, [event.description, isFootball])
    const footballGroup = useMemo(() => {
        switch (event.description.split('/')[1]) {
            case 'ACSOP':
                return locale.football.acsop
            case 'BCSOP':
                return locale.football.bcsop
            case 'CCSOP':
                return locale.football.ccsop
            case 'DCSOP':
                return locale.football.dcsop
            case 'NEGYED':
                return locale.football.negyed
            case 'ELODONT':
                return locale.football.elodont
            case 'DONTO':
                return locale.football.donto
            case 'BRONZ':
                return locale.football.bronz
            case 'KISB':
                return locale.football.kisb
            default:
                return ''
        }
    }, [event.description])
    return (
        <div
            className={`mb-3 flex flex-col justify-between rounded-lg bg-gray-600 p-2 ${
                className ?? ''
            }`}
        >
            <div>
                <h3 className="px-2 text-2xl font-bold">{event.name}</h3>
                <h4 className="px-2 text-sm">
                    {isFootball ? footballGroup : event.organiser}
                </h4>
                {isFootball ? (
                    <div className="flex w-full items-center justify-center gap-2 py-2">
                        <div className="rounded-lg bg-slate-100 px-6 py-2 text-xl font-semibold text-gray-700">
                            {footballScore[0]}
                        </div>
                        -
                        <div className="rounded-lg bg-slate-100 px-6 py-2 text-xl font-semibold text-gray-700">
                            {footballScore[1]}
                        </div>
                    </div>
                ) : (
                    <p>{event.description}</p>
                )}
            </div>
            <div>
                <div className="flew-row mb-1 mt-2 flex w-full justify-between">
                    <div className="rounded-full bg-gray-400 px-3">
                        {isFootball
                            ? new Date(event.starts_at).toLocaleString()
                            : event.location?.name ?? 'Ismeretlen'}
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
                        {locale.view}
                    </Button>
                </ButtonGroup>
            </div>
        </div>
    )
}

export default EventCard
