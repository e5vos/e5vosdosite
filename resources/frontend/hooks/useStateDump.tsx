import { RootState, useSelector } from 'lib/store'

export const dumpState = (state: RootState) => ({ ...state, auth: 'REDACTED' })

export const useStateDump = () => useSelector(dumpState)

export default useStateDump
