import { useCallback, useState } from 'react'

const useDelay = <T extends (...args: unknown[]) => unknown>(
    callback: T,
    delay = 500
): ((...args: Parameters<T>) => void) => {
    const [dt, setDt] = useState<number | undefined>()

    return useCallback(
        (...params: Parameters<T>) => {
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
