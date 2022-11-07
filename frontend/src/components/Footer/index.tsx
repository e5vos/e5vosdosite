import { ReactComponent as Donci } from "assets/donci.svg";

const Footer = () => {
  return (
    <div className="flex-shrink-0">
      <div className="container mx-auto">
        <div className="mx-auto w-fit text-center my-5">
          <div className="flex flex-col align-middle mx-auto text-center">
            <Donci fill="white" className="w-10 mx-auto hover:animate-wiggle" />
            <div>Eötvös DÖ</div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Footer;
