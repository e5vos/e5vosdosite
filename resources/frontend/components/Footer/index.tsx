import { ReactComponent as Donci } from "assets/donci.svg";

import Locale from "lib/locale";

const locale = Locale({
    hu: "Eötvös DÖ",
    en: "Eötvös DÖ",
});

const Footer = () => {
    return (
        <div className="flex-shrink-0">
            <div className="container mx-auto">
                <div className="mx-auto my-5 w-fit text-center">
                    <div className="mx-auto flex flex-col text-center align-middle">
                        <Donci
                            fill="white"
                            className="mx-auto w-10 hover:animate-wiggle"
                        />
                        <div>{locale}</div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Footer;
