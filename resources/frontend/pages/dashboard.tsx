import useUser from 'hooks/useUser'

import eventAPI from 'lib/api/eventAPI'
import Locale from 'lib/locale'

import EventCard from 'components/EventCard'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        title: `Üdv,`,
        subtitle: `Üdvözlünk az ${
            import.meta.env.VITE_EVENT_HU
        } koordinálásáért felelős rendszerben. Itt többek közt élőben követheted a focimeccsek állását.`,
        login: 'Bejelentkezés',
        events: 'Események',
        eventsBySlot: 'Események időpont szerint',
        teams: 'Csapatok',
        suInformation: 'DÖ Információk',
        presentationSignup: 'Előadásjelentkezés',
    },
    en: {
        title: 'Hi,',
        subtitle: `Welcome to the system responsible for coordinating the ${
            import.meta.env.VITE_EVENT_EN
        }. Here you can follow the football matches.`,
        login: 'Login',
        events: 'Events',
        eventsBySlot: 'Events by slot',
        teams: 'Teams',
        suInformation: 'SU Information',
        presentationSignup: 'Presentation signup',
    },
})

const Dashboard = () => {
    const { user } = useUser(true)

    const { data: events, isFetching: isEventsFetching } =
        eventAPI.useGetEventsQuery()

    const currentNonFootballEvents = events?.filter(
        (event) =>
            new Date(event.starts_at) < new Date() &&
            new Date(event.ends_at) > new Date() &&
            event.slot.name !== 'Foci'
    )

    const currentFootballMatch = events?.find(
        (event) =>
            new Date(event.starts_at) < new Date() &&
            new Date(event.ends_at) > new Date() &&
            event.slot.name === 'Foci'
    )

    if (!events) return <Loader />
    return (
        <div className="w-full">
            <div className="container mx-auto px-2">
                <div className="my-5 grid max-w-fit sm:grid-cols-1 md:mx-28 md:grid-cols-2 lg:grid-cols-3">
                    <div className="sm:col-span-1 md:col-span-2 lg:col-span-3">
                        <h1 className="mb-2 text-2xl font-bold">
                            {locale.title +
                                ' ' +
                                (user?.name ?? 'Felhasználó') +
                                '!'}
                        </h1>
                        <h3 className="text-lg">{locale.subtitle}</h3>
                    </div>
                    <div className="mb-2 mt-4 flex flex-wrap items-center justify-between rounded-lg bg-gray-600 px-4 py-2 sm:col-span-1 md:col-span-2 lg:col-span-3">
                        <h4 className="text-lg font-semibold">
                            {currentFootballMatch?.name}
                        </h4>
                        <div className="flex items-center justify-center gap-2 py-2">
                            <div className="rounded-lg bg-slate-100 px-6 py-2 text-xl font-semibold text-gray-700">
                                {currentFootballMatch?.description.substring(
                                    0,
                                    currentFootballMatch?.description.indexOf(
                                        '-'
                                    )
                                )}
                            </div>
                            -
                            <div className="rounded-lg bg-slate-100 px-6 py-2 text-xl font-semibold text-gray-700">
                                {currentFootballMatch?.description.substring(
                                    currentFootballMatch?.description.indexOf(
                                        '-'
                                    ) + 1
                                )}
                            </div>
                        </div>
                    </div>
                    <div className="pb-2 pt-3 sm:col-span-1 md:col-span-2 lg:col-span-3">
                        <h2 className="text-lg font-semibold">
                            Jelenlegi programok
                        </h2>
                    </div>
                    {isEventsFetching ? (
                        <Loader />
                    ) : (
                        <>
                            {currentNonFootballEvents?.map((event) => (
                                <EventCard event={event} key={event.id} />
                            ))}
                        </>
                    )}
                </div>
            </div>
        </div>
    )
}

export default Dashboard
