const ButtonGroup = ({
  children,
  className,
}: React.DetailedHTMLProps<
  React.HTMLAttributes<HTMLDivElement>,
  HTMLDivElement
>) => {
  return <div className={`buttongroup ${className}`}>{children}</div>;
};
export default ButtonGroup;
