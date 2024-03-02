const ErrorMsgBox = ({
    errorShown,
    errormsg,
}: {
    errorShown: boolean
    errormsg: string
}) => {
    return (
        <div
            className={`mx-auto my-4 max-w-6xl rounded-lg bg-red py-3 text-center transition delay-100 duration-500 ease-in-out ${
                !errorShown && 'hidden'
            }`}
        >
            <h4 className="text-xl font-semibold">Hiba</h4>
            <hr className="mx-3 bg-white shadow-md shadow-white" />
            <p>{errormsg}</p>
        </div>
    )
}

export default ErrorMsgBox
