import { useCallback, useState } from 'react'

const useDelay = <Args extends unknown[], Return = void>(
    callback: (...args: Args) => Return,
    delay = 500
): ((...args: Args) => void) => {
    const [dt, setDt] = useState<number | undefined>()

    return useCallback(
        (...params: Args) => {
            clearTimeout(dt)
            setDt(
                window.setTimeout(() => {
                    callback(...params)
                }, delay)
            )
        },
        [dt, delay, callback]
    )
}

export default useDelay
