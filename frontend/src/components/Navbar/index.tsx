import Navbar from "components/UIKit/Navbar";
import useUser from "hooks/useUser";

const CustomNavbar = () => {
  const { user } = useUser(false);
  return (
    <Navbar brand={<Navbar.Brand>{user ? user.name : "Főoldal"}</Navbar.Brand>}>
      <Navbar.Link href="/eloadas">Előadásjelentkezés</Navbar.Link>
      <Navbar.Link href="/logout">Kijelentkezés</Navbar.Link>
    </Navbar>
  );
};
export default CustomNavbar;
