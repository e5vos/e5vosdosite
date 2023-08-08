import useUser from "hooks/useUser";

import Navbar from "components/UIKit/Navbar";

const CustomNavbar = () => {
  const { user } = useUser(false);
  return (
    <Navbar brand={<Navbar.Brand>{user ? user.name : "Főoldal"}</Navbar.Brand>}>
      <Navbar.Link href="/eloadas">Előadásjelentkezés</Navbar.Link>
      {!user && <Navbar.Link href="/login">Bejelentkezés</Navbar.Link>}
      {user && <Navbar.Link href="/logout">Kijelentkezés</Navbar.Link>}
    </Navbar>
  );
};
export default CustomNavbar;
