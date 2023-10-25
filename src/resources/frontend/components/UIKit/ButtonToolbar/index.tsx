import React from "react";

const ButtonToolbar = ({
    children,
}: React.DetailedHTMLProps<
    React.HTMLAttributes<HTMLDivElement>,
    HTMLDivElement
>) => {
    return <div className="flex flex-row">{children}</div>;
};
export default ButtonToolbar;
