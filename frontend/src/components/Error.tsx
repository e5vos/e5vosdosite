import { ReactNode } from "react";
type HTTPErrorCode = 400 | 401 | 403 | 404 | 500 | 502 | 503 | 504;

const Error = ({
  code,
  message,
  children,
}: {
  code: HTTPErrorCode;
  message?: string;
  children?: ReactNode;
}) => {
  return (
    <div className="flex h-full flex-col items-center justify-center">
      <div className="text-9xl font-bold">{code}</div>
      {children ?? (
        <div className="text-2xl">{message ?? <>Hiba történt</>}</div>
      )}
    </div>
  );
};

export default Error;
