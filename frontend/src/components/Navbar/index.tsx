import useUser from "hooks/useUser";

import Locale from "lib/locale";

import Navbar from "components/UIKit/Navbar";

const locale = Locale({
  hu: {
    presentationSignup: "Előadásjelentkezés",
    login: "Bejelentkezés",
    logout: "Kijelentkezés",
  },
  en: {
    presentationSignup: "Presentation signup",
    login: "Login",
    logout: "Logout",
  },
});

const CustomNavbar = () => {
  const { user } = useUser(false);
  return (
    <Navbar brand={<Navbar.Brand>{user ? user.name : "Főoldal"}</Navbar.Brand>}>
      <Navbar.Link href="/eloadas">{locale.presentationSignup}</Navbar.Link>
      {!user && <Navbar.Link href="/login">{locale.login}</Navbar.Link>}
      {user && <Navbar.Link href="/logout">{locale.logout}</Navbar.Link>}
    </Navbar>
  );
};
export default CustomNavbar;
