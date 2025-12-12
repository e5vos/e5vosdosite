import useConfirm, { ConfirmDialogProps } from 'hooks/useConfirm'
import useScannerHandler, { ScannerError } from 'hooks/useScannerHandler'
import useUser from 'hooks/useUser'
import { useCallback, useEffect, useState } from 'react'
import { QrReader } from 'react-qr-reader'
import { useParams } from 'react-router-dom'

import { TeamMember } from 'types/models'

import eventAPI from 'lib/api/eventAPI'
import { isOrganiser, isScanner } from 'lib/gates'
import Locale from 'lib/locale'

import Error, { HTTPErrorCode } from 'components/Error'
import Button from 'components/UIKit/Button'
import Card from 'components/UIKit/Card'
import Dialog from 'components/UIKit/Dialog'
import Form from 'components/UIKit/Form'
import Loader from 'components/UIKit/Loader'

declare global {
    interface Window {
        scan?: (code: string) => Promise<void>
    }
}

const locale = Locale({
    hu: {
        scanner: 'QR Olvasó',
        ispresentq: (name: string) => `${name} jelen van?`,
        dialogtitle: 'Csapattag jelenléte',
        confirmed: 'jelenlét rögzítve',
        yes: 'Igen',
        no: 'Nem',
        code: 'Kód',
        submit: 'Beküldés',
        error: (error: ScannerError): string => {
            switch (error) {
                case 'NoE5N':
                    return 'Az E5N mód ki van kapcsolva'
                case 'PermissionDenied':
                    return 'Hozzáférés megtagadva'
                case 'SignupRequired':
                    return 'Az eseményre jelentkezni kellett volna'
                case 'TooFewAttendees':
                    return 'Nincs elég csapattag'
                case 'TooManyAttendees':
                    return 'Túl sok csapattag'
                case 'Unknown':
                    return 'Ismeretlen hiba'
            }
        },
    },
    en: {
        scanner: 'QR Scanner',
        ispresentq: (name: string) => `Is ${name} present?`,
        dialogtitle: 'Team member presence',
        confirmed: 'presence confirmed',
        yes: 'Yes',
        no: 'No',
        code: 'Code',
        submit: 'Submit',
        error: (error: ScannerError): string => {
            switch (error) {
                case 'NoE5N':
                    return 'E5N mode is disabled'
                case 'PermissionDenied':
                    return 'Permission denied'
                case 'SignupRequired':
                    return 'You had to sign up for the event'
                case 'TooFewAttendees':
                    return 'Not enough team members'
                case 'TooManyAttendees':
                    return 'Too many team members'
                case 'Unknown':
                    return 'Unknown error'
            }
        },
    },
})

const useMemberConfigDialog = (selectedMember: TeamMember | null) =>
    useCallback(
        ({ handleConfirm, handleCancel }: ConfirmDialogProps) => {
            if (!selectedMember) return <></>
            return (
                <Dialog title={locale.dialogtitle} closable={false}>
                    <div>
                        {locale.ispresentq(
                            `${selectedMember.name} - ${selectedMember.ejg_class}`
                        )}
                    </div>
                    <div className="mt-3 w-full">
                        <Button
                            variant="success"
                            onClick={handleConfirm}
                            className="w-1/2"
                        >
                            {locale.yes}
                        </Button>
                        <Button
                            variant="danger"
                            onClick={handleCancel}
                            className="w-1/2"
                        >
                            {locale.no}
                        </Button>
                    </div>
                </Dialog>
            )
        },
        [selectedMember]
    )

const Scanner = () => {
    const { eventid } = useParams()
    const { data: event, error } = eventAPI.useGetEventQuery({
        id: Number(eventid),
    })

    const [code, setCode] = useState<string | null>(null)

    const [errorMessage, setErrorMessage] = useState<string | null>(null)
    const [successMessage, setSuccessMessage] = useState<string | null>(null)

    const [selectedMember, setSelectedMember] = useState<null | TeamMember>(
        null
    )
    const memberConfigDialogTemplate = useMemberConfigDialog(selectedMember)
    const [MemberConfirmDialog, confirmMember] = useConfirm(
        memberConfigDialogTemplate
    )

    const scan = useScannerHandler({
        event: event ?? { id: -1 },
        onSuccess: (attendance) => {
            setSuccessMessage(`${attendance.name} ${locale.confirmed}`)
            setTimeout(() => setSuccessMessage(null), 2000)
        },
        onError: (error) => {
            console.log('error', error)
            setErrorMessage(error)
            setTimeout(() => setErrorMessage(null), 2000)
        },
        teamMemberPrompt: async (member) => {
            setSelectedMember(member)
            return await confirmMember()
        },
    })
    useEffect(() => {
        window.scan = scan
    }, [scan])

    const { user } = useUser()

    if (error && 'status' in error)
        return <Error code={error.status as HTTPErrorCode} />
    if (!event || !user) return <Loader />
    if (!isScanner(event)(user) && !isOrganiser(event)(user))
        return <Error code={403} />
    return (
        <div className="container mx-auto ">
            <div className="text-center">
                <h1 className=" text-4xl font-bold">
                    {event.name} - {locale.scanner}
                </h1>
                <h3>
                    {user.name} - {user?.ejg_class}
                </h3>
            </div>
            {(errorMessage || successMessage) && (
                <Card
                    title={errorMessage ?? successMessage ?? ''}
                    className={`mt-2 ${
                        errorMessage ? 'bg-red-500' : 'bg-green-500'
                    }`}
                />
            )}
            <MemberConfirmDialog />

            <Form
                className="mx-auto max-w-lg"
                onSubmit={async (e) => {
                    if (code) {
                        await scan(code)
                        setSelectedMember(null)
                        setCode(null)
                    }
                    e.preventDefault()
                }}
            >
                <Form.Group>
                    <Form.Label>{locale.code}</Form.Label>
                    <Form.Control onChange={(t) => setCode(t.target.value)} />
                    <Button
                        onClick={async () => {
                            if (code) {
                                await scan(code)
                                setSelectedMember(null)
                                setCode(null)
                            }
                        }}
                    >
                        {locale.submit}
                    </Button>
                </Form.Group>
            </Form>
            <QrReader
                onResult={async (result) => {
                    if (result) {
                        await scan(result.getText())
                        setSelectedMember(null)
                        setCode(null)
                    }
                }}
                constraints={{ facingMode: 'environment' }}
                className="mx-auto max-h-[1/3] max-w-4xl hover:opacity-75"
            />
        </div>
    )
}
export default Scanner
