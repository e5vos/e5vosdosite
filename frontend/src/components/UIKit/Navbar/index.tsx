import { ReactNode } from "react";
import { ReactComponent as Caret } from "./assets/caret.svg";
import { ReactComponent as MobileMenu } from "./assets/mobileMenu.svg";
import { Menu, Transition } from "@headlessui/react";
import useIsMobile from "hooks/useIsMobile";
const Brand = ({ href, children }: { href?: string; children: ReactNode }) => {
  const Tag = href ? "a" : "span";
  return (
    <Tag href={href} className="flex items-center">
      <img
        src="https://flowbite.com/docs/images/logo.svg"
        className="mr-3 h-6 sm:h-10"
        alt="Flowbite Logo"
      />
      <span className="self-center text-xl font-semibold whitespace-nowrap dark:text-white">
        {children}
      </span>
    </Tag>
  );
};

const DropDownItem = ({
  href,
  children,
}: {
  href?: string;
  children: ReactNode;
}) => {
  const Tag = href ? "a" : "span";
  return (
    <Menu.Item>
      {({ active }) => (
        <Tag
          href={href}
          className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
        >
          {children}
        </Tag>
      )}
    </Menu.Item>
  );
};

const DropDownComponent = ({
  toggle,
  children,
}: {
  toggle: ReactNode;
  children: ReactNode;
}) => {
  return (
    <Menu as="li">
      {({ open }) => (
        <>
          <Menu.Button className="flex justify-between items-center py-2 pr-4 pl-3 w-full font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
            {toggle}
            <Caret />
          </Menu.Button>
          <Transition
            show={open}
            enter="transition duration-100 ease-out"
            enterFrom="transform scale-95 opacity-0"
            enterTo="transform scale-100 opacity-100"
            leave="transition duration-75 ease-out"
            leaveFrom="transform scale-100 opacity-100"
            leaveTo="transform scale-95 opacity-0"
            static
          >
            <Menu.Items
              static
              className="z-10 w-44 font-normal bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
              style={{
                visibility: open ? "visible" : "hidden",
              }}
            >
              <ul
                className="py-1 text-sm text-gray-700 dark:text-gray-400"
                aria-labelledby="dropdownLargeButton"
              >
                {children}
              </ul>
            </Menu.Items>
          </Transition>
        </>
      )}
    </Menu>
  );
};

const Link = ({ href, children }: { href: string; children: ReactNode }) => {
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

const Navbar = ({
  children,
  brand,
}: {
  children: ReactNode;
  brand: ReactNode;
}) => {
  const isMobile = useIsMobile();
  return (
    <nav className="px-2 bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">
      <Menu
        as="div"
        className="container flex flex-wrap justify-between items-center mx-auto"
      >
        {({ open }) => (
          <>
            <>{brand}</>
            <Menu.Button
              className="inline-flex justify-center items-center ml-3 text-gray-400 rounded-lg md:hidden hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-500"
              aria-controls="mobile-menu-2"
              aria-expanded="false"
            >
              <span className="sr-only">Open main menu</span>
              <MobileMenu />
            </Menu.Button>
            <Transition
              show={!isMobile || open}
              enter="transition duration-100 ease-out"
              enterFrom="transform scale-95 opacity-0"
              enterTo="transform scale-100 opacity-100"
              leave="transition duration-75 ease-out"
              leaveFrom="transform scale-100 opacity-100"
              leaveTo="transform scale-95 opacity-0"
              static
            >
              <Menu.Items
                className="w-full md:block md:w-auto"
                id="mobile-menu"
                static
                style={{
                  visibility: !isMobile || open ? "visible" : "hidden",
                }}
              >
                <ul className="flex flex-col p-4 mt-4 bg-gray-50 rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                  {children}
                </ul>
              </Menu.Items>
            </Transition>
          </>
        )}
      </Menu>
    </nav>
  );
};

const DropDown = Object.assign(DropDownComponent, {
  Item: DropDownItem,
});
export default Object.assign(Navbar, {
  Link,
  Dropdown: DropDown,
  Brand,
});
