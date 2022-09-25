import { useMediaQuery } from "react-responsive"

const useIsMobile = () => {
    return useMediaQuery({ query: "(max-width: 768px)" })
}
export default useIsMobile