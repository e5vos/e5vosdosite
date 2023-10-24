import { ReactNode } from "react";

import ButtonGroup from "../ButtonGroup";

const Card = ({
  title,
  subtitle,
  children,
  className,
  titleClassName,
  subtitleClassName,
  buttonBar: buttonGroup,
  ...rest
}: React.DetailedHTMLProps<
  React.HTMLAttributes<HTMLDivElement>,
  HTMLDivElement
> & {
  title?: ReactNode;
  subtitle?: ReactNode;
  children: ReactNode;
  buttonBar?: ReturnType<typeof ButtonGroup>;
  titleClassName?: string;
  subtitleClassName?: string;
}) => (
  <div
    className={`mb-3 flex flex-col justify-between rounded-lg bg-gray-100 p-2 ${
      className ?? ""
    }`}
    {...rest}
  >
    {title && (
      <div>
        <h3 className={`px-2 text-center font-bold ${titleClassName ?? ""}`}>
          {title}
        </h3>
        {subtitle && (
          <>
            <hr />
            <h4 className={`px-2 text-center ${subtitleClassName ?? ""}`}>
              {subtitle}
            </h4>
          </>
        )}
      </div>
    )}
    <div className="my-2 px-2">{children}</div>
    {buttonGroup}
  </div>
);

export default Card;
