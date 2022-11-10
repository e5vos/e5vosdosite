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
      className={`border-b-2 border-white bg-transparent text-white  invalid:border-red invalid:text-red-500 focus:border-gray-400 invalid:focus:text-white ${
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
    <span className="mr-1 font-bold underline underline-offset-4">
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
      className={`form-group mb-3 flex flex-row items-center justify-center ${
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
