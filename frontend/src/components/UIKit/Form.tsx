import React, { ReactNode } from "react";


export const Control = ({className, ...rest}:React.DetailedHTMLProps<React.InputHTMLAttributes<HTMLInputElement>,HTMLInputElement>) => {
    return <input className={`${className}`} {...rest}/>
}

const Form = ({className,children,...rest}:{children:ReactNode} & React.DetailedHTMLProps<React.InputHTMLAttributes<HTMLFormElement>,HTMLFormElement>) => {
    return <form className={`${className}`} {...rest}>{children}</form>
}

export default Object.assign(Form,{Control});