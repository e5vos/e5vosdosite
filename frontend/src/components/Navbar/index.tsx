import Navbar from "components/UIKit/Navbar";

const CustomNavbar = () => {
  return (
    <Navbar brand={<Navbar.Brand>Főoldal</Navbar.Brand>}>
      <Navbar.Link href="/eloadas">Előadásjelentkezés</Navbar.Link>
    </Navbar>
  );
};
export default CustomNavbar;
