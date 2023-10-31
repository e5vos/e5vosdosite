import { ReactComponent as Donci } from "assets/donci.svg";
import { Link } from "react-router-dom";

import Locale from "lib/locale";

const locale = Locale({
    hu: {
        title: "Eötvös DÖ",
        privacypolicy: "Adatvédelmi nyilatkozat",
        contact: "Kapcsolat",
        github: "GitHub",
    },
    en: {
        title: "Eötvös DÖ",
        privacypolicy: "Privacy policy",
        contact: "Contact",
        github: "GitHub",
    },
});

const Footer = () => {
    return (
        <div className="flex-shrink-0">
            <div className="container mx-auto">
                <div className="mx-auto mt-5 w-fit text-center">
                    <div className="mx-auto flex flex-col text-center align-middle">
                        <Donci
                            fill="white"
                            className="mx-auto w-10 hover:animate-wiggle"
                        />
                        <div>{locale.title}</div>
                    </div>
                </div>
                <div className="mx-auto flex w-1/2">
                    <Link
                        to="/privacypolicy"
                        className="mr-auto flex flex-1 justify-center"
                    >
                        {locale.privacypolicy}
                    </Link>
                    <Link
                        to="mailto:dev.do@e5vos.hu"
                        className="flex flex-1 justify-center"
                    >
                        {locale.contact}
                    </Link>
                    <Link
                        to="https://github.com/difcsi/e5vosdosite"
                        className="ml-auto flex flex-1 justify-center"
                    >
                        {locale.github}
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default Footer;
