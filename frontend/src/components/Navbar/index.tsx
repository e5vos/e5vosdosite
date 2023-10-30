import useUser from "hooks/useUser";

import Locale from "lib/locale";

import Navbar from "components/UIKit/Navbar";

const locale = Locale({
  hu: {
    presentationSignup: "Előadásjelentkezés",
    homepage: "Főoldal",
    login: "Bejelentkezés",
    logout: "Kijelentkezés",
    info: "DÖ Információk"
  },
  en: {
    presentationSignup: "Presentation signup",
    homepage: "Homepage",
    login: "Login",
    logout: "Logout",
    info: "SU Information"
  },
});

const CustomNavbar = () => {
  const { user } = useUser(false);
  return (
    <Navbar
      brand={<Navbar.Brand href="/">{user ? user.name : locale.homepage}</Navbar.Brand>}
    >
      <Navbar.Link href="https://info.e5vosdo.hu">{locale.info}</Navbar.Link>
      <Navbar.Link href="/eloadas">{locale.presentationSignup}</Navbar.Link>
      {!user && <Navbar.Link href="/login">{locale.login}</Navbar.Link>}
      {user && <Navbar.Link href="/logout">{locale.logout}</Navbar.Link>}
    </Navbar>
  );
};
export default CustomNavbar;
