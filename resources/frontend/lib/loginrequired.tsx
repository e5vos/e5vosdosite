import useUser from 'hooks/useUser'
import React from 'react'

import Loader from 'components/UIKit/Loader'

export const loginRequired = <P, T extends React.ComponentType<P>>(
    Component: T
): React.ComponentType<P> => {
    type Props = React.ComponentProps<T>

    const GatedComponent: React.FC<Props> = (props: Props) => {
        const { isLoading } = useUser()
        if (isLoading) return <Loader />
        return <Component {...props} />
    }

    GatedComponent.displayName = `loginRequired(${Component.displayName || Component.name || 'Component'})`

    return GatedComponent as React.ComponentType<P>
}
