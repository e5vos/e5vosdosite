import Cookies from 'js-cookie'

const refreshCSRF = async () => {
    if (!Cookies.get('XSRF-TOKEN')) {
        await fetch(`${import.meta.env.VITE_BACKEND}/sanctum/csrf-cookie`, {
            credentials: 'include',
        })
    }
    return Cookies.get('XSRF-TOKEN')
}

export default refreshCSRF
