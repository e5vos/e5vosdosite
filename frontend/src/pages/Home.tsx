import Button from "components/UIKit/Button";
import { Link } from "react-router-dom";
import { ReactComponent as Donci } from "assets/donci.svg";
const Home = () => {
  return (
    <div className="w-full">
      <div className="container mx-auto">
        <div className="mx-auto my-5 max-w-fit text-center">
          <h1 className="mb-3 text-6xl font-bold">Eötvös DÖ</h1>
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
