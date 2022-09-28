type HTTPErrorCode = 400 | 401 | 403 | 404 | 500 | 502 | 503 | 504;

const Error = ({code}:{code:HTTPErrorCode}) => {
    return (
        <div className="flex flex-col items-center justify-center h-full">
        <div className="text-9xl font-bold">{code}</div>
        <div className="text-2xl font-bold">Hiba</div>
        </div>
    );
}

export default Error;