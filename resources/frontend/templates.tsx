import { ReactNode } from "react";

import Footer from "components/Footer";
import Navbar from "components/Navbar";

export const BaseLayout = ({ children }: { children: ReactNode }) => {
    return (
        <>
            <Navbar />
            <main className="mx-2">{children}</main>
            <Footer />
        </>
    );
};
