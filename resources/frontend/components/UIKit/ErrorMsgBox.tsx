const ErrorMsgBox = ({
    errorShown,
    errormsg,
}: {
    errorShown: boolean
    errormsg: string
}) => {
    return (
        <div
            className={`bg-red mx-auto my-4 max-w-6xl rounded-lg py-3 text-center transition delay-100 duration-500 ease-in-out ${
                !errorShown && 'hidden'
            }`}
        >
            <h4 className="text-xl font-semibold">Hiba</h4>
            <hr className="bg-foreground shadow-foreground mx-3 shadow-md" />
            <p>{errormsg}</p>
        </div>
    )
}

export default ErrorMsgBox
