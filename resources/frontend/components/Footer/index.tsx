import { ReactComponent as Donci } from "assets/donci.svg";
import { Link } from "react-router-dom";

import Locale from "lib/locale";

const locale = Locale({
    hu: {
        title: "Eötvös József Gimnázium Diákönkormányzata",
        privacypolicy: "Adatvédelmi nyilatkozat",
        contact: "Kapcsolat",
        github: "GitHub",
    },
    en: {
        title: "Student Union of Eötvös József Grammar School",
        privacypolicy: "Privacy policy",
        contact: "Contact",
        github: "GitHub",
    },
});

const Footer = () => {
    return (
        <div className="flex justify-between bg-gray-600 px-8 py-3 align-middle">
            <div className="flex w-full justify-start gap-2 text-center align-middle">
                <Donci fill="white" className="w-10 hover:animate-wiggle" />
                <p className="my-auto">{locale.title}</p>
            </div>
            <div className="mt-2 flex w-full justify-end gap-2">
                <Link to="/privacypolicy">{locale.privacypolicy}</Link>
                <Link to="mailto:dev.do@e5vos.hu">{locale.contact}</Link>
                <Link to="https://github.com/difcsi/e5vosdosite">
                    {locale.github}
                </Link>
            </div>
        </div>
    );
};

export default Footer;
