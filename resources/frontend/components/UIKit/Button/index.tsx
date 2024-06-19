import React from 'react'

const Button = ({
    variant = 'primary',
    type = 'button',
    children,
    className,
    ...rest
}: React.DetailedHTMLProps<
    React.ButtonHTMLAttributes<HTMLButtonElement>,
    HTMLButtonElement
> & {
    variant?:
        | 'primary'
        | 'outline-primary'
        | 'secondary'
        | 'outline-secondary'
        | 'danger'
        | 'outline-danger'
        | 'success'
        | 'outline-success'
        | 'warning'
        | 'outline-warning'
        | 'info'
        | 'outline-info'
    type?: 'button' | 'submit' | 'reset'
    className?: string
}) => {
    if (type === 'submit' && !variant) variant = 'success'

    let buttonclass = 'outline outline-5 dark:outline-2 outline-offset-0  '
    switch (variant) {
        case 'primary':
            buttonclass +=
                'dark:bg-blue-400 dark:outline-blue-400 bg-blue-300 outline-blue-300 enabled:hover:dark:bg-blue-700 enabled:hover:dark:bg-blue-600 enabled:hover:bg-blue-500 disabled:dark:bg-blue-700 disabled:bg-blue-600 text-black dark:text-white enabled:hover:text-white disabled:text-white '
            break
        case 'outline-primary':
            buttonclass +=
                'outline-blue-400 enabled:hover:bg-blue-400 disabled:bg-blue-700 text-black dark:text-white'
            break
        case 'secondary':
            buttonclass +=
                'dark:bg-white bg-slate-200 dark:outline-white outline-slate-200 enabled:hover:dark:bg-gray-600 enabled:hover:bg-slate-500 disabled:dark:bg-gray-600 disabled:bg-slate-400 text-black enabled:hover:text-white disabled:dark:text-white'
            break
        case 'outline-secondary':
            buttonclass +=
                'dark:outline-white outline-slate-300 enabled:hover:dark:bg-white enabled:hover:bg-slate-300 disabled:bg-white text-black dark:text-white enabled:hover:text-black disabled:text-black'
            break
        case 'danger':
            buttonclass +=
                'dark:bg-red-500 bg-[#ff6162] dark:outline-red-500 outline-[#ff6162] enabled:hover:dark:bg-red-700 enabled:hover:bg-red-500 disabled:dark:bg-red-700 disabled:bg-red-600 text-black dark:text-white enabled:hover:text-white disabled:text-white'
            break
        case 'outline-danger':
            buttonclass +=
                'outline-red enabled:hover:bg-red disabled:bg-red text-black dark:text-white enabled:hover:text-white disabled:text-white'
            break
        case 'success':
            buttonclass +=
                'bg-green outline-green enabled:hover:bg-green-700 disabled:bg-green-700 text-black dark:text-white enabled:hover:text-white disabled:text-white'
            break
        case 'outline-success':
            buttonclass +=
                'outline-green enabled:hover:bg-green disabled:bg-green text-black dark:text-white enabled:hover:text-black disabled:text-black'
            break
        case 'warning':
            buttonclass +=
                'bg-goldenrod outline-goldenrod enabled:hover:bg-goldenrod-700 disabled:bg-goldenrod-700 text-black enabled:hover:text-white disabled:text-white'
            break
        case 'outline-warning':
            buttonclass +=
                'outline-goldenrod enabled:hover:bg-goldenrod disabled:bg-godlenrod text-black dark:text-white enabled:hover:text-black disabled:text-black'
            break
        case 'info':
            buttonclass +=
                'dark:bg-blue-600 bg-blue-400 dark:outline-blue-600 outline-blue-400 enabled:hover:dark:bg-blue-700 enabled:hover:bg-blue-600 disabled:bg-blue-700 text-black dark:text-white enabled:hover:text-white disabled:text-white'
            break
        case 'outline-info':
            buttonclass +=
                'outline-blue-600 enabled:hover:bg-blue-600 disabled:bg-blue text-black dark:text-white enabled:hover:text-white disabled:text-white'
            break
        default:
    }

    return (
        <button
            {...rest}
            type={type}
            className={`rounded px-4 py-2 font-semibold ${buttonclass} ${
                className ?? ''
            }`}
        >
            {children}
        </button>
    )
}
export default Button
