import { useCallback, useState } from "react";

const useDelayedSet = <T>(
    setValue: React.Dispatch<React.SetStateAction<T>>,
    delay = 500,
) => {
    const [dt, setDt] = useState<number | undefined>();

    return useCallback(
        (value: T) => {
            clearTimeout(dt);
            setDt(
                window.setTimeout(() => {
                    setValue((value) => value);
                }, delay),
            );
        },
        [dt, delay, setValue],
    );
};

export default useDelayedSet;
