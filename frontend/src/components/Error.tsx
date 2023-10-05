import { ReactComponent as Donci } from "assets/donci.svg";
import { ReactNode } from "react";

import Locale from "lib/locale";

export type HTTPErrorCode = 400 | 401 | 403 | 404 | 500 | 502 | 503 | 504;

const locale = Locale({
  hu: {
    code: {
      400: "Hibás kérés",
      401: "Nincs jogosultságod a kért oldal megtekintéséhez",
      403: "Nincs jogosultságod a kért oldal megtekintéséhez",
      404: "A kért oldal nem található",
      500: "Hiba történt a kiszolgáló oldalon",
      502: "Hiba történt a kiszolgáló oldalon",
      503: "Hiba történt a kiszolgáló oldalon",
      504: "Hiba történt a kiszolgáló oldalon"
    },
    unknown: "Ismeretlen hiba történt"
  },
  en: {
    code: {
      400: "Bad request",
      401: "You are not authorized to view the requested page",
      403: "You are not authorized to view the requested page",
      404: "The requested page was not found",
      500: "An error occured on the server",
      502: "An error occured on the server",
      503: "An error occured on the server",
      504: "An error occured on the server"
    },
    unknown: "An unknown error occured"
  }
});

const getDefaultMessage = (code: HTTPErrorCode): string => {
  return locale.code[code] ?? locale.unknown;
};

const Error = ({
  code,
  message,
  children
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
