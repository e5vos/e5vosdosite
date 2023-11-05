import { useCallback, useState } from "react";

const useDelay = <T extends (...args: any[]) => void>(
    callback: T,
    delay = 500,
) => {
    const [dt, setDt] = useState<number | undefined>();

    return useCallback(
        (...params: Parameters<T>) => {
            clearTimeout(dt);
            setDt(
                window.setTimeout(() => {
                    callback(...params);
                }, delay),
            );
        },
        [dt, delay, callback],
    );
};

export default useDelay;
