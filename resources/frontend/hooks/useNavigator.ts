import { useEffect, useState } from "react";

/**
 * Small wrapper around browser navigator object
 * Supports navigator null on page load
 * @returns navigator object if exists
 */
export default function useNavigator() {
    const [navigator, setnavigator] = useState<Navigator | undefined>();
    useEffect(() => {
        setnavigator(window.navigator);
    }, []);
    return navigator;
}
