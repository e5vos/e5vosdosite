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
> & {
  variant?:
    | "primary"
    | "outline-primary"
    | "secondary"
    | "outline-secondary"
    | "danger"
    | "outline-danger"
    | "success"
    | "outline-success";
}) => {
  if (type === "submit" && !variant) variant = "success";

  let buttonclass;
  switch (variant) {
    case "primary":
      buttonclass = "bg-blue-500 hover:bg-blue-700 disabled:bg-blue-900";
      break;
    case "outline-primary":
      buttonclass =
        "bg-white hover:bg-blue-500 text-blue-700 font-semibold hover:text-white border border-blue-500 hover:border-transparent rounded";
      break;
    case "secondary":
      buttonclass =
        "border-hidden rounded-3xl shadow-md bg-white disabled:bg-gray-500";
      break;
    case "outline-secondary":
      buttonclass =
        "border-2 border-gray-500 rounded-3xl shadow-md bg-white disabled:bg-gray-500";
      break;
    case "danger":
      buttonclass = "bg-red-500 hover:bg-red-700";
      break;
    case "outline-danger":
      buttonclass =
        "bg-white hover:bg-red-500 text-red-700 font-semibold hover:text-white border border-red-500 hover:border-transparent rounded";
      break;
    case "success":
      buttonclass = "bg-green-500 hover:bg-green-700";
      break;
    case "outline-success":
      buttonclass =
        "bg-white hover:bg-green-500 text-green-700 font-semibold hover:text-white border border-green-500 hover:border-transparent rounded";
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
