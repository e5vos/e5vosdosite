import Footer from "components/Footer";
import Navbar from "components/Navbar/Navbar";
import { ReactNode } from "react";

export const BaseLayout = ({ children }: { children: ReactNode }) => {
  return (
    <>
      <Navbar />
      <main>{children}</main>
      <Footer />
    </>
  );
};
