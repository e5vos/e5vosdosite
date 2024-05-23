import eventAPI from 'lib/api/eventAPI'
import Locale from 'lib/locale'

import EventCard from 'components/EventCard'
import Card from 'components/UIKit/Card'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        title: `${import.meta.env.VITE_EVENT_HU_SHORT} - Mérkőzések`,
        groupMatches: `Csoportmérkőzések`,
        team: {
            bulgaria: `🇧🇬 Bulgária`,
            czech: `🇨🇿 Csehország`,
            portugal: `🇵🇹 Portugália`,
            poland: `🇵🇱 Lengyelország`,
            france: `🇫🇷 Franciaország`,
            italy: `🇮🇹 Olaszország`,
            belgium: `🇧🇪 Belgium`,
            denmark: `🇩🇰 Dánia`,
            hungary: `🇭🇺 Magyarország`,
            germany: `🇩🇪 Németország`,
            spain: `🇪🇸 Spanyolország`,
            croatia: `🇭🇷 Horvátország`,
            greece: `🇬🇷 Görögország`,
        },
    },
    en: {
        title: `${import.meta.env.VITE_EVENT_HU_SHORT} - Matches`,
        groupMatches: `Group matches`,
        team: {
            bulgaria: `🇧🇬 Bulgaria`,
            czech: `🇨🇿 Czech Republic`,
            portugal: `🇵🇹 Portugal`,
            poland: `🇵🇱 Poland`,
            france: `🇫🇷 France`,
            italy: `🇮🇹 Italy`,
            belgium: `🇧🇪 Belgium`,
            denmark: `🇩🇰 Denmark`,
            hungary: `🇭🇺 Hungary`,
            germany: `🇩🇪 Germany`,
            spain: `🇪🇸 Spain`,
            croatia: `🇭🇷 Croatia`,
            greece: `🇬🇷 Greece`,
        },
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
                    <p>{locale.team.bulgaria}</p>
                    <p>{locale.team.czech}</p>
                    <p>{locale.team.portugal}</p>
                    <p>{locale.team.poland}</p>
                </Card>
                <Card title="B csoport" className="!justify-start">
                    <p>{locale.team.france}</p>
                    <p>{locale.team.italy}</p>
                    <p>{locale.team.belgium}</p>
                </Card>
                <Card title="C csoport" className="!justify-start">
                    <p>{locale.team.denmark}</p>
                    <p>{locale.team.hungary}</p>
                    <p>{locale.team.germany}</p>
                </Card>
                <Card title="D csoport" className="!justify-start">
                    <p>{locale.team.spain}</p>
                    <p>{locale.team.croatia}</p>
                    <p>{locale.team.greece}</p>
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
