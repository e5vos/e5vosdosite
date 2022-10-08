import React from "react";

const Button = ({
  variant = "primary",
  type = "button",
  children,
  className,
  ...rest
}: React.DetailedHTMLProps<
  React.ButtonHTMLAttributes<HTMLButtonElement>,
  HTMLButtonElement
> & { variant?: string }) => {
  if (variant === "submit") type = "submit";

  let buttonclass;
  switch (variant) {
    case "primary":
      buttonclass = "bg-blue-500 hover:bg-blue-700 disabled:bg-blue-900";
      break;
    case "secondary":
      buttonclass = "border-hidden rounded-3xl shadow-md bg-white";
      break;
    case "danger":
      buttonclass = "bg-red-500 hover:bg-red-700";
      break;
    case "outline-primary":
      buttonclass = "bg-white hover:bg-blue-500 text-blue-700 font-semibold hover:text-white border border-blue-500 hover:border-transparent rounded";  
      break;
    default:
      buttonclass = "bg-blue-500 hover:bg-blue-700";
  }

  return (
    <button
      {...rest}
      type={type}
      className={`py-2 px-4 font-semibold rounded ${buttonclass} ${
        className ?? ""
      }`}
    >
      {children}
    </button>
  );
};
export default Button;
