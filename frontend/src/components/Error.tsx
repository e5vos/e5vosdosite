import { ReactNode } from "react";
import { ReactComponent as Donci } from "assets/donci.svg";
export type HTTPErrorCode = 400 | 401 | 403 | 404 | 500 | 502 | 503 | 504;

const getDefaultMessage = (code: HTTPErrorCode): string => {
  switch (code) {
    case 400:
      return "Hibás kérés";
    case 401:
      return "Nincs jogosultságod a kért oldal megtekintéséhez";
    case 403:
      return "Nincs jogosultságod a kért oldal megtekintéséhez";
    case 404:
      return "A kért oldal nem található";
    case 500:
      return "Hiba történt a kiszolgáló oldalon";
    case 502:
      return "Hiba történt a kiszolgáló oldalon";
    case 503:
      return "Hiba történt a kiszolgáló oldalon";
    case 504:
      return "Hiba történt a kiszolgáló oldalon";
    default:
      return "Ismeretlen hiba történt";
  }
};

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
      <div className="text-9xl font-bold">
        <Donci className="mx-auto animate-pulse fill-red" />
        {code}{" "}
      </div>
      {children ?? (
        <div className="text-2xl">{message ?? getDefaultMessage(code)}</div>
      )}
    </div>
  );
};

export default Error;
