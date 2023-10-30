import { ReactComponent as Donci } from "assets/donci.svg";
import { Link } from "react-router-dom";

import Locale from "lib/locale";

import Button from "components/UIKit/Button";

const locale = Locale({
  hu: {
    title: "Eötvös DÖ",
    presentationSignup: "Előadásjelentkezés",
  },
  en: {
    title: "Eötvös DÖ",
    presentationSignup: "Presentation signup",
  },
});

const Home = () => {
  return (
    <div className="w-full">
      <div className="container mx-auto">
        <div className="mx-auto my-5 max-w-fit text-center">
          <h1 className="mb-3 text-6xl font-bold">{locale.title}</h1>
          <Button variant="primary">
            <Link to="/eloadas">{locale.presentationSignup}</Link>
          </Button>
          <div>
            <Donci fill="white" />
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;
