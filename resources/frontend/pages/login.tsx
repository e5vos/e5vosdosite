import useUser from 'hooks/useUser'
import { useEffect } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'

import { isTeacher } from 'lib/gates'
import Locale from 'lib/locale'
import { useSelector } from 'lib/store'

import LoginForm from 'components/Login'
import Button from 'components/UIKit/Button'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        title: 'Bejelentkezés',
        alreadyLoggedIn: 'Már be vagy jelentkezve',
        presentationSignup: 'Előadásjelentkezés',
        presentationAttendance: 'Előadás Jelenlétí Ívek',
        legal: 'A bejelentkezés gombra kattintva elfogadod az',
        legalLink: 'adatkezelési nyilatkozatotunkat',
    },
    en: {
        title: 'Login',
        alreadyLoggedIn: 'You are already logged in',
        presentationSignup: 'Presentation signup',
        presentationAttendance: 'Presentation Attendance Sheets',
        legal: 'By clicking the login button you accept our',
        legalLink: 'privacy policy',
    },
})

const LoginRecoveryPanel = () => {
    const { user } = useUser()
    const navigate = useNavigate()
    const params = useParams()
    const token = useSelector((state) => state.auth.token)
    useEffect(() => {
        if (token !== '') {
            navigate(params.next ?? '/eloadas', { replace: true })
        }
    }, [navigate, params.next, token])
    if (!user) return <Loader />
    return (
        <div>
            <div>{locale.alreadyLoggedIn}</div>
            {isTeacher(user) ? (
                <Button onClick={() => navigate('/eloadas/kezel')}>
                    {locale.presentationAttendance}
                </Button>
            ) : (
                <Button onClick={() => navigate('/eloadas')}>
                    {locale.presentationSignup}
                </Button>
            )}
        </div>
    )
}

const LoginPage = () => {
    const { user } = useUser(false)

    return (
        <div className="mx-auto text-center">
            <h1 className="mb-8 text-4xl font-bold">{locale.title}</h1>
            {user ? <LoginRecoveryPanel /> : <LoginForm />}
            <div className="mt-4 text-sm text-gray-500 italic">
                {locale.legal}{' '}
                <Link to="/privacypolicy" target="_blank" className="underline">
                    {locale.legalLink}
                </Link>
            </div>
        </div>
    )
}

export default LoginPage
