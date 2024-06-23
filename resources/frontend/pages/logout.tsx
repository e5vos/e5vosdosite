import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { authSlice } from 'reducers/authReducer'

import { useDispatch } from 'lib/store'

import Loader from 'components/UIKit/Loader'

const LogoutPage = () => {
    const navigate = useNavigate()
    const dispatch = useDispatch()
    useEffect(() => {
        dispatch(authSlice.actions.clearToken())
        navigate('/', { replace: true })
    }, [navigate, dispatch])
    return <Loader />
}

export default LogoutPage
