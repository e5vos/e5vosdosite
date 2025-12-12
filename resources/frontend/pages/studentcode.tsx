import { useFormik } from 'formik'
import useUser from 'hooks/useUser'
import { useEffect } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import * as Yup from 'yup'

import baseAPI from 'lib/api'
import Locale from 'lib/locale'

import Button from 'components/UIKit/Button'
import Form from 'components/UIKit/Form'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        studentCode: 'Diákkód',
        inputStudentCode: 'Diákkód megadása',
        invalidCode: 'Hibás diákkód',
        serverError: 'Szerverhiba. Létezik az osztályod?',
        studentCodeRequired: 'Diákkód megadása kötelező',
        mustBeStudentCode: 'Diákkódnak kell lennie',
        submit: 'Diákkód jóváhagyása',
        unknownError: 'Ismeretlen hiba',
    },
    en: {
        studentCode: 'Student code',
        inputStudentCode: 'Input student code',
        invalidCode: 'Invalid student code',
        serverError: 'Server error. Does your class exist?',
        studentCodeRequired: 'Student code is required',
        mustBeStudentCode: 'Must be a student code',
        submit: 'Submit student code',
        unknownError: 'Unknown error',
    },
})
const StudentCodePage = () => {
    const [updateStudentCode] = baseAPI.useSetStudentCodeMutation()
    const navigate = useNavigate()
    const { next } = useParams()
    const { user, error, isLoading } = useUser(false)

    useEffect(() => {
        if (!user && error) {
            navigate('/login?next=/studentcode')
        }
        if (user?.e5code) {
            navigate(next ?? '/')
        }
    }, [user, error, navigate, next])

    const formik = useFormik({
        initialValues: {
            studentCode: '',
        },
        validationSchema: Yup.object({
            studentCode: Yup.string()
                .matches(
                    /^20(\d{2})([A-FN])(\d{2})EJG(\d{3})$/,
                    locale.mustBeStudentCode
                )
                .required(locale.studentCodeRequired),
        }),
        validateOnChange: false,
        onSubmit: async (values) => {
            try {
                await updateStudentCode(values.studentCode).unwrap()
                navigate(next ?? '/dashboard')
            } catch (error) {
                switch ((error as { status: number }).status) {
                    case 400:
                        formik.setFieldError('studentCode', locale.invalidCode)
                        break
                    case 500:
                        formik.setFieldError('studentCode', locale.serverError)
                        break
                    default:
                        formik.setFieldError('studentCode', locale.unknownError)
                        break
                }
            }
        },
    })
    if (isLoading) return <Loader />
    return (
        <div className="container mx-auto">
            <div className="mx-auto max-w-xl text-center">
                <h1 className="mb-2 text-4xl font-bold">
                    {locale.inputStudentCode}
                </h1>
                <hr className="mb-3 bg-gray-50" />
                <Form onSubmit={formik.handleSubmit}>
                    <Form.Group>
                        <Form.Label className="text-4xl">
                            {locale.studentCode}:
                        </Form.Label>
                        <Form.Control
                            name="studentCode"
                            type="text"
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            className="text-center"
                            invalid={!!formik.errors.studentCode}
                        />
                        <Form.Text className="text-red-300">
                            {formik.errors.studentCode}
                        </Form.Text>
                    </Form.Group>
                    <Form.Group>
                        <Button type="submit">{locale.submit}</Button>
                    </Form.Group>
                </Form>
            </div>
        </div>
    )
}

export default StudentCodePage
