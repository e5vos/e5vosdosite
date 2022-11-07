import { ReactNode } from "react";
import { ReactComponent as Caret } from "./assets/caret.svg";
import { ReactComponent as MobileMenu } from "./assets/mobileMenu.svg";
import { Menu, Transition } from "@headlessui/react";
import useIsMobile from "hooks/useIsMobile";
import { ReactComponent as Donci } from "assets/donci.svg";
const Brand = ({ href, children }: { href?: string; children: ReactNode }) => {
  const Tag = href ? "a" : "span";
  return (
    <Tag href={href} className="flex items-center">
      <Donci fill="white" className="h-full w-12 mr-5" />
      <span className="self-center text-xl font-semibold whitespace-nowrap">
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
        <Tag href={href} className="block py-2 px-4 hover:bg-gray-100">
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
          <Menu.Button className="flex justify-between items-center py-2 pr-4 pl-3 w-full font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto">
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
              className="z-10 w-44 font-normal bg-white rounded divide-y divide-gray-100 shadow"
              style={{
                visibility: open ? "visible" : "hidden",
              }}
            >
              <ul
                className="py-1 text-sm text-gray-700"
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
        className="block py-2 pr-4 pl-3 text-white rounded hover:bg-gray-100 md:hover:bg-inherit md:hover:text-gray-50 md:p-0"
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
    <nav className="px-2 py-2 mb-2 bg-gray-400 border-gray-200 rounded text-white">
      <Menu as="div" className="">
        {({ open }) => (
          <div>
            <div className="container flex flex-wrap justify-between items-center mx-auto">
              <>{brand}</>
              <Menu.Button
                className="inline-flex justify-center items-center ml-3 md:hidden  rounded-lg hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300"
                aria-controls="mobile-menu-2"
                aria-expanded="false"
              >
                <span className="sr-only">Főmenü megnyitása</span>
                <MobileMenu fill="white" className="h-10" />
              </Menu.Button>
              <ul className="hidden md:flex p-4 flex-row space-x-8 mt-0 text-sm font-medium rounded-lg">
                {children}
              </ul>
            </div>
            <Transition
              show={!isMobile || open}
              enter="transition duration-200 ease-out"
              enterFrom="transform scale-95 opacity-0"
              enterTo="transform scale-100 opacity-100"
              leave="transition duration-200 ease-out"
              leaveFrom="transform scale-100 opacity-100"
              leaveTo="transform scale-95 opacity-0"
              static
            >
              <Menu.Items
                className={`w-full ${isMobile && open ? "visible" : "hidden"}`}
                id="mobile-menu"
                static
                as="div"
              >
                <ul className="flex flex-col p-4 mt-4 rounded-lg border font-medium">
                  {children}
                </ul>
              </Menu.Items>
            </Transition>
          </div>
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
