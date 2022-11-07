import Button from "components/UIKit/Button";
import { Link } from "react-router-dom";
import { ReactComponent as Donci } from "assets/donci.svg";
const Home = () => {
  return (
    <div className="w-full">
      <div className="container mx-auto">
        <div className="mx-auto max-w-fit text-center my-5">
          <h1 className="mb-3 font-bold text-6xl">Eötvös DÖ</h1>
          <Button variant="primary">
            <Link to="/eloadas">Előadásjelentkezés</Link>
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
