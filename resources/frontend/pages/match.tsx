import eventAPI from 'lib/api/eventAPI'
import Locale from 'lib/locale'

import EventCard from 'components/EventCard'
import Card from 'components/UIKit/Card'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        title: `${import.meta.env.VITE_EVENT_HU_SHORT} - Mérkőzések`,
        groupMatches: `Csoportmérkőzések`,
    },
    en: {
        title: `${import.meta.env.VITE_EVENT_HU_SHORT} - Matches`,
        groupMatches: `Group matches`,
    },
})

const Match = () => {
    const { data: events, isFetching: isEventsFetching } =
        eventAPI.useGetEventsQuery()

    const agroup = events?.filter(
        (event) =>
            event.slot.name === 'Foci' && event.description.includes('ACSOP')
    )

    const bgroup = events?.filter(
        (event) =>
            event.slot.name === 'Foci' && event.description.includes('BCSOP')
    )

    const cgroup = events?.filter(
        (event) =>
            event.slot.name === 'Foci' && event.description.includes('CCSOP')
    )

    const dgroup = events?.filter(
        (event) =>
            event.slot.name === 'Foci' && event.description.includes('DCSOP')
    )

    return (
        <div className="mx-5">
            <div className="container mx-auto">
                <h1 className="max-w-f pb-4 text-center text-4xl font-bold">
                    {locale.title}
                </h1>
            </div>
            <div className="mx-auto mt-2 grid w-full gap-2 text-center sm:grid-cols-2 md:grid-cols-4">
                <Card title="A csoport" className="!justify-start">
                    <p>🇧🇬 Bulgária</p>
                    <p>🇨🇿 Csehország</p>
                    <p>🇵🇹 Portugália</p>
                    <p>🇵🇱 Lengyelország</p>
                </Card>
                <Card title="B csoport" className="!justify-start">
                    <p>🇫🇷 Franciaország</p>
                    <p>🇮🇹 Olaszország</p>
                    <p>🇧🇪 Belgium</p>
                </Card>
                <Card title="C csoport" className="!justify-start">
                    <p>🇩🇰 Dánia</p>
                    <p>🇭🇺 Magyarország</p>
                    <p>🇩🇪 Németország</p>
                </Card>
                <Card title="D csoport" className="!justify-start">
                    <p>🇪🇸 Spanyolország</p>
                    <p>🇭🇷 Horvátország</p>
                    <p>🇬🇷 Görögország</p>
                </Card>
            </div>
            <div className="rounded-lg bg-gray-600 px-4 py-2 text-center font-semibold">
                {locale.groupMatches}
            </div>
            <div className="mx-auto mt-2 grid w-full gap-2 text-center sm:grid-cols-2 md:grid-cols-4">
                <div className="flex w-auto flex-col">
                    {isEventsFetching ? (
                        <Loader />
                    ) : (
                        <>
                            {agroup?.map((event) => (
                                <EventCard event={event} key={event.id} />
                            ))}
                        </>
                    )}
                </div>
                <div className="flex w-auto flex-col">
                    {isEventsFetching ? (
                        <Loader />
                    ) : (
                        <>
                            {bgroup?.map((event) => (
                                <EventCard event={event} key={event.id} />
                            ))}
                        </>
                    )}
                </div>
                <div className="flex w-auto flex-col">
                    {isEventsFetching ? (
                        <Loader />
                    ) : (
                        <>
                            {cgroup?.map((event) => (
                                <EventCard event={event} key={event.id} />
                            ))}
                        </>
                    )}
                </div>
                <div className="flex w-auto flex-col">
                    {isEventsFetching ? (
                        <Loader />
                    ) : (
                        <>
                            {dgroup?.map((event) => (
                                <EventCard event={event} key={event.id} />
                            ))}
                        </>
                    )}
                </div>
            </div>
        </div>
    )
}

export default Match
