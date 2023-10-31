import { ReactComponent as Donci } from "assets/donci.svg";
import { useEffectOnce } from "hooks/useEffectOnce";
import { ReactNode, useState } from "react";

import Locale from "lib/locale";

import Button from "./UIKit/Button";
import Loader from "./UIKit/Loader";

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
            504: "Hiba történt a kiszolgáló oldalon",
        },
        unknown: "Ismeretlen hiba történt",
        acknowledge: "Rendben",
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
            504: "An error occured on the server",
        },
        unknown: "An unknown error occured",
        acknowledge: "Acknowledge",
    },
});

const getDefaultMessage = (code: HTTPErrorCode): string => {
    return locale.code[code] ?? locale.unknown;
};

const Error = ({
    code,
    message,
    children,
    onClose,
}: {
    code: HTTPErrorCode;
    message?: string;
    children?: ReactNode;
    onClose?: () => void;
}) => {
    return (
        <div className="flex h-full flex-col items-center justify-center">
            <div className="text-9xl font-bold">
                <Donci className="mx-auto animate-pulse fill-red" />
                {code}{" "}
            </div>
            {children ?? (
                <div>
                    <div className="text-2xl">
                        {message ?? getDefaultMessage(code)}
                    </div>
                    {onClose && (
                        <Button
                            variant="danger"
                            className="mt-3 w-full"
                            onClick={onClose}
                        >
                            {locale.acknowledge}
                        </Button>
                    )}
                </div>
            )}
        </div>
    );
};

export const BackendError = () => {
    const [data, setData] = useState<{
        code: HTTPErrorCode;
        message?: string;
    }>();
    useEffectOnce(() => {
        setData({
            code: window.statusCode as HTTPErrorCode,
            message: window.message,
        });
        return () => {
            window.statusCode = undefined;
            window.message = undefined;
        };
    });
    if (!data) return <Loader />;
    return <Error code={data.code} message={data.message} />;
};

export default Error;
