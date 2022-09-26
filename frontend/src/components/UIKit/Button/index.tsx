import React from "react";

const Button = ({variant = "primary",type = "button",children,className, active,...rest}:React.DetailedHTMLProps<React.ButtonHTMLAttributes<HTMLButtonElement>,HTMLButtonElement> & {variant?:string, active?: boolean}) => {
    if(variant === "submit") type="submit"
    const classname = {
        "primary": "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
        "submit": "block bg-black text-white px-4 py-2 rounded-md text-center",
        "danger": "bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded",
    }[variant]
    return <button  {...rest} type={type} className={classname+ " " + className ?? ""} >{children}</button>
}
export default Button;