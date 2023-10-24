import { ReactNode } from "react";

import Footer from "components/Footer";
import Navbar from "components/Navbar";

export const BaseLayout = ({ children }: { children: ReactNode }) => {
  return (
    <>
      <Navbar />
      <main>{children}</main>
      <Footer />
    </>
  );
};
