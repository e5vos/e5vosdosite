import React, { ReactNode, FormEventHandler } from "react";
import { HTMLInputProps } from "./helpers";

const Control = ({
  invalid,
  className,
  onInvalid: onInvalidCallback,
  ...rest
}: {
  invalid?: boolean;
  onInvalid?: FormEventHandler<HTMLInputElement>;
} & HTMLInputProps<HTMLInputElement>) => {
  const onInvalid: FormEventHandler<HTMLInputElement> = (e) => {
    e.preventDefault();
    onInvalidCallback?.(e);
  };

  return (
    <input
      pattern={invalid ? "" : undefined}
      onInvalid={onInvalid}
      {...rest}
      className={`text-white bg-transparent border-b-2 border-white  invalid:border-red invalid:text-red-500 focus:border-gray-400 invalid:focus:text-white ${
        className ?? ""
      }`}
    />
  );
};

const Text = ({
  className,
  children,
  ...rest
}: HTMLInputProps<HTMLDivElement>) => {
  return (
    <div {...rest} className={className}>
      {children}
    </div>
  );
};

const Label = ({
  className,
  children,
  ...rest
}: HTMLInputProps<HTMLSpanElement>) => {
  return (
    <span className="font-bold mb-3 mr-1 underline underline-offset-4">
      {children}
    </span>
  );
};

const Group = ({
  className,
  children,
  ...rest
}: HTMLInputProps<HTMLLabelElement>) => {
  return (
    <label
      {...rest}
      className={`flex flex-row justify-center align-middle form-group ${
        className ?? ""
      }`}
    >
      {children}
    </label>
  );
};

const Form = ({
  className,
  children,
  ...rest
}: { children: ReactNode } & React.DetailedHTMLProps<
  React.InputHTMLAttributes<HTMLFormElement>,
  HTMLFormElement
>) => {
  return (
    <form {...rest} className={`${className}`}>
      {children}
    </form>
  );
};

const Check = ({
  className,
  ...rest
}: HTMLInputProps<HTMLInputElement> & { type?: never }) => {
  return (
    <input
      {...rest}
      type="checkbox"
      className={`disabled:bg-gray-300 ${className}`}
    />
  );
};

const Select = ({ children, ...rest }: HTMLInputProps<HTMLSelectElement>) => {
  return <select {...rest}>{children}</select>;
};

export default Object.assign(Form, {
  Control,
  Text,
  Label,
  Group,
  Check,
  Select,
});
