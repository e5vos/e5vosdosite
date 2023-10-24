import { Combobox } from "@headlessui/react";
import React, { FormEventHandler, ReactNode } from "react";

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
  invalid,
  onInvalid: onInvalidCallback,
  ...rest
}: HTMLInputProps<HTMLInputElement> & {
  type?: never;
  invalid?: boolean;
  onInvalid?: FormEventHandler<HTMLInputElement>;
}) => {
  return (
    <input
      {...rest}
      pattern={invalid ? "" : undefined}
      onInvalid={onInvalidCallback}
      type="checkbox"
      className={`disabled:bg-gray-300 ${className}`}
    />
  );
};

const Select = ({ children, ...rest }: HTMLInputProps<HTMLSelectElement>) => {
  return (
    <select className="bg-gray" {...rest}>
      {children}
    </select>
  );
};

const ComboBoxOption = ({
  children,
  key,
  value,
}: {
  children: ReactNode;
  key: any;
  value: any;
}) => {
  return (
    <Combobox.Option key={key} value={value}>
      {children}
    </Combobox.Option>
  );
};

const ComboBox = <T = any,>({
  options,
  filter,
  onChange,
  renderElement,
}: {
  options: T[];
  filter: (s: string) => (e: T) => boolean;
  onChange?: (e: T | null) => void;
  renderElement: (e: T) => ReactNode;
}) => {
  const [query, setQuery] = React.useState("");
  const [selected, setSelected] = React.useState<T | null>(null);
  const filteredOptions = options.filter(filter(query));

  return (
    <Combobox
      value={selected}
      onChange={(e) => {
        setSelected(e);
        if (onChange) onChange(e);
      }}
    >
      <Combobox.Input
        value={query}
        onChange={(e) => setQuery(e.target.value)}
        className="border-b-2 border-white bg-transparent text-white focus:border-gray-400"
      />
      <Combobox.Options>
        {filteredOptions.map((option, index) => (
          <ComboBoxOption key={index} value={option}>
            {renderElement(option)}
          </ComboBoxOption>
        ))}
      </Combobox.Options>
    </Combobox>
  );
};

export default Object.assign(Form, {
  Control,
  Text,
  Label,
  Group,
  Check,
  Select,
  ComboBox,
});
