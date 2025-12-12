import { User } from 'types/models'

import { GateFunction, TailParams } from 'lib/gates'

const useGate = <T extends GateFunction>(
    user: User | undefined,
    gate: T,
    ...rest: TailParams<T>
): boolean | string => {
    if (!user) return false
    if (!gate(user, ...rest)) return gate.message ?? false
    return true
}

export default useGate
