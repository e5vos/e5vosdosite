import useUser from 'hooks/useUser'
import { Link } from 'react-router-dom'

import Locale from 'lib/locale'

import Button from 'components/UIKit/Button'
import { Subtitle } from 'components/UIKit/Typography'

const locale = Locale({
    hu: {
        title: 'Kezdőlap',
        subtitle: `Üdvözlünk az ${
            import.meta.env.VITE_EVENT_HU
        } koordinálásáért felelős rendszerben. Itt többek közt élőben követheted a meccsek eredményeit.`,
        login: 'Bejelentkezés',
        events: 'Események',
        eventsBySlot: 'Események időpont szerint',
        teams: 'Csapatok',
        suInformation: 'DÖ Információk',
        presentationSignup: 'Előadásjelentkezés',
        recommendToLogin: 'A legjobb élmény érdekében jelentkezz be!',
    },
    en: {
        title: 'Home',
        subtitle: `Welcome to the system responsible for coordinating the ${
            import.meta.env.VITE_EVENT_EN
        }. Here you can follow the football matches.`,
        login: 'Login',
        events: 'Events',
        eventsBySlot: 'Events by slot',
        teams: 'Teams',
        suInformation: 'SU Information',
        presentationSignup: 'Presentation signup',
        recommendToLogin: 'For the best experience, please log in!',
    },
})

const Home = () => {
    const { user } = useUser(false)

    return (
        <div className="w-full">
            <div className="container mx-auto">
                <div className="mx-auto my-5 max-w-fit text-center md:mx-28">
                    <h1 className="mb-3 text-4xl font-bold">{locale.title}</h1>
                    <h2 className="text-2xl">{locale.subtitle}</h2>
                    <div className="px-auto mx-auto mt-5 flex flex-col justify-center gap-2 md:mx-28">
                        {!user && (
                            <>
                                <p>{locale.recommendToLogin}</p>
                                <Link to="/login">
                                    <Button
                                        className="!w-full"
                                        variant="outline-primary"
                                    >
                                        {locale.login}
                                    </Button>
                                </Link>
                            </>
                        )}
                        <Link to="/esemeny">
                            <Button
                                className="!w-full"
                                variant="outline-secondary"
                            >
                                {locale.events}
                            </Button>
                        </Link>
                        {/*
            <Link to="https://info.e5vosdo.hu">
              <Button className="!w-full" variant="outline-secondary">
                {locale.suInformation}
              </Button>
            </Link>
            <Link to="/csapat">
              <Button className="!w-full" variant="outline-warning">
                {locale.teams}
              </Button>
            </Link>
            <Link to="/eloadas">
                            <Button className="!w-full" variant="outline-info">
                                {locale.presentationSignup}
                            </Button>
                        </Link>
            <Link to="/sav">
              <Button className="!w-full" variant="outline-danger">
                {locale.eventsBySlot}
              </Button>
            </Link>
            */}
                    </div>
                </div>
            </div>
        </div>
    )
}

export default Home
