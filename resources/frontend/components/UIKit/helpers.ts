import React from 'react'

export type HTMLInputProps<T> = React.DetailedHTMLProps<
    React.InputHTMLAttributes<T>,
    T
>
