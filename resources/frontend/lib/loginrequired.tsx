import useUser from 'hooks/useUser'
import React from 'react'

import Loader from 'components/UIKit/Loader'

export const loginRequired = <P extends object>(
    Component: React.ComponentType<P>
): React.ComponentType<P> => {
    const GatedComponent = (props: P) => {
        const { isLoading } = useUser()
        if (isLoading) return <Loader />
        return <Component {...props} />
    }

    GatedComponent.displayName = `loginRequired(${Component.displayName || Component.name || 'Component'})`

    return GatedComponent
}
