import React, { ReactNode } from "react";
import { HTMLInputProps } from "./helpers";


const Control = ({className, ...rest}: HTMLInputProps<HTMLInputElement>) => {
    return <input {...rest} className={` ${className ?? ""}`} />
}

const Text = ({className, children, ...rest}:HTMLInputProps<HTMLDivElement>) => {
    return <div {...rest}>{children}</div>
}

const Label = ({className, children, ...rest}:HTMLInputProps<HTMLSpanElement>) => {
    return <span className={"text-gray-700"}>{children}</span>
}

const Group = ({className, children, ...rest}:HTMLInputProps<HTMLLabelElement>) => {
    return <label {...rest} className={`block form-group ${className ?? ""}`} >{children}</label>
}


const Form = ({className,children,...rest}:{children:ReactNode} & React.DetailedHTMLProps<React.InputHTMLAttributes<HTMLFormElement>,HTMLFormElement>) => {
    return <form {...rest} className={`${className}`} >{children}</form>
}

const Check = ({...rest}:HTMLInputProps<HTMLInputElement> & {type?: never}) => {
    return <input {...rest} type="checkbox" />
}

const Select = ({children, ...rest}:HTMLInputProps<HTMLSelectElement>) => {
    return <select {...rest}>{children}</select>
}





export default Object.assign(Form,{
    Control,
    Text,
    Label,
    Group,
    Check,
    Select
});