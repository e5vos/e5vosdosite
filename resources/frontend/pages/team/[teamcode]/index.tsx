import useUser from 'hooks/useUser'
import { useState } from 'react'
import QRCode from 'react-qr-code'
import { useParams } from 'react-router-dom'

import teamAPI from 'lib/api/teamAPI'
import Locale from 'lib/locale'

import Error from 'components/Error'
import TeamCard from 'components/Team/TeamCard'
import Button from 'components/UIKit/Button'
import Dialog from 'components/UIKit/Dialog'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        teamnotfound: 'A csapat nem található!',
        team_code: (team_name: string) => `A(z) ${team_name} csapat kódja:`,
        openQR: 'Csapat kódjának megtekintése',
    },
    en: {
        teamnotfound: 'The team was not found!',
        team_code: (team_name: string) =>
            `The code of the ${team_name} team is:`,
        openQR: 'View team code',
    },
})

const TeamPage = () => {
    const { teamcode } = useParams<{ teamcode: string }>()

    const [openQR, setOpenQR] = useState(false)
    const { user, error: userError } = useUser()
    const { data: team, error } = teamAPI.useGetTeamQuery({
        code: teamcode ?? '-1',
    })

    if (error || userError)
        return <Error code={404} message={locale.teamnotfound} />
    if (!team || !user) return <Loader />
    return (
        <div>
            <Dialog
                title={locale.team_code(team.name)}
                open={openQR}
                onClose={() => setOpenQR(false)}
            >
                <QRCode className="max-w-full" value={team.code} />
            </Dialog>
            <div className="text-center">
                <Button
                    className="mx-auto mb-3 w-full max-w-4xl "
                    onClick={() => setOpenQR(true)}
                >
                    {locale.openQR}
                </Button>
            </div>
            <TeamCard team={team} currentUser={user} />
        </div>
    )
}

export default TeamPage
