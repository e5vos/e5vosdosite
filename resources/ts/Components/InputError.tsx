export default function InputError({ message, className = '' }:{message:string, className:string}) {
    return message ? <p className={'text-sm text-red-600 ' + className}>{message}</p> : null;
}
