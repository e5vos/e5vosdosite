import { ReactNode } from "react";
import { ReactComponent as Caret } from "./assets/caret.svg";
import { ReactComponent as MobileMenu } from "./assets/mobileMenu.svg";
const Navbrand = ({
  href,
  children,
}: {
  href: string;
  children: ReactNode;
}) => {
  return (
    <a href={href} className="flex items-center">
      <img
        src="https://flowbite.com/docs/images/logo.svg"
        className="mr-3 h-6 sm:h-10"
        alt="Flowbite Logo"
      />
      <span className="self-center text-xl font-semibold whitespace-nowrap dark:text-white">
        {children}
      </span>
    </a>
  );
};

const DropDownItem = ({
  href,
  children,
}: {
  href: string;
  children: ReactNode;
}) => {
  return (
    <li>
      <a
        href={href}
        className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
      >
        {children}
      </a>
    </li>
  );
};

const DropDown = () => {
  return (
    <li>
      <button
        id="dropdownNavbarLink"
        data-dropdown-toggle="dropdownNavbar"
        className="flex justify-between items-center py-2 pr-4 pl-3 w-full font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent"
      >
        Dropdown <Caret />
      </button>
      <div
        id="dropdownNavbar"
        className="hidden z-10 w-44 font-normal bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
        style={{
          position: "absolute",
          inset: "0px auto auto 0px",
          margin: "0px",
          transform: "translate(0px, 1110px)",
        }}
        data-popper-reference-hidden=""
        data-popper-escaped=""
        data-popper-placement="bottom"
      >
        <ul
          className="py-1 text-sm text-gray-700 dark:text-gray-400"
          aria-labelledby="dropdownLargeButton"
        >
          <DropDownItem href="#">Test</DropDownItem>
          <DropDownItem href="#">Test</DropDownItem>
        </ul>
        <ul className="py-1">
          <DropDownItem href="#">Test</DropDownItem>
        </ul>
      </div>
    </li>
  );
};

const NavLink = ({ href, children }: { href: string; children: ReactNode }) => {
  return (
    <li>
      <a
        href={href}
        className="block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"
      >
        {children}
      </a>
    </li>
  );
};

const Navbar = () => {
  return (
    <nav className="px-2 bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">
      <div className="container flex flex-wrap justify-between items-center mx-auto">
        <Navbrand href="#">Brand</Navbrand>
        <button
          data-collapse-toggle="mobile-menu"
          type="button"
          className="inline-flex justify-center items-center ml-3 text-gray-400 rounded-lg md:hidden hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-500"
          aria-controls="mobile-menu-2"
          aria-expanded="false"
        >
          <span className="sr-only">Open main menu</span>
          <MobileMenu />
        </button>
        <div className="hidden w-full md:block md:w-auto" id="mobile-menu">
          <ul className="flex flex-col p-4 mt-4 bg-gray-50 rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
            <NavLink href="#">Contact</NavLink>
            <DropDown />
            <li />
            <NavLink href="#">Contact</NavLink>
            <NavLink href="#">Contact</NavLink>
          </ul>
        </div>
      </div>
    </nav>
  );
};
export default Navbar;
