import useUser from 'hooks/useUser'
import { MouseEventHandler } from 'react'
import { useParams } from 'react-router-dom'

import { Attender, isAttenderTeam, isAttenderUser } from 'types/models'

import eventAPI from 'lib/api/eventAPI'
import { isOperator, isTeacher } from 'lib/gates'
import Locale from 'lib/locale'
import { reverseNameOrder } from 'lib/util'

import { gated } from 'components/Gate'
import Button from 'components/UIKit/Button'
import Form from 'components/UIKit/Form'
import Loader from 'components/UIKit/Loader'

const locale = Locale({
    hu: {
        attendanceSheet: 'Jelenléti ív',
        delete: 'Törlés',
        refresh: 'Frissítés',
    },
    en: {
        attendanceSheet: 'Attendance sheet',
        delete: 'Delete',
        refresh: 'Refresh',
    },
})

const AttendancePage = () => {
    const { eventid } = useParams<{ eventid: string }>()
    const { data: event, isLoading: isEventLoading } =
        eventAPI.useGetEventQuery({ id: Number(eventid ?? -1) })
    const {
        data: participantsData,
        isLoading: isParticipantsLoading,
        isFetching: isParticipantsFetching,
        refetch,
    } = eventAPI.useGetEventParticipantsQuery({ id: Number(eventid ?? -1) })

    const [deleteAttendance] = eventAPI.useCancelSignUpMutation()
    const { user } = useUser()
    const participants =
        participantsData
            ?.slice()
            .sort((a, b) =>
                reverseNameOrder(a.name).localeCompare(reverseNameOrder(b.name))
            ) ?? []

    const [toggleAPI, { isLoading }] = eventAPI.useAttendMutation()

    if (isEventLoading || isParticipantsLoading || !event) return <Loader />

    const toggle =
        (attending: Attender): MouseEventHandler<HTMLInputElement> =>
        async (e) => {
            const target = e.currentTarget
            e.preventDefault()

            const res = await toggleAPI({
                attender: isAttenderTeam(attending)
                    ? attending.code
                    : attending.id,
                event: event,
                present: target.checked,
            })
            if ('error' in res) {
                alert('Error')
            } else {
                target.checked = !target.checked
            }
        }

    const deleteAttendanceAction = async (attending: Attender) => {
        await deleteAttendance({
            attender: String(
                isAttenderUser(attending) ? attending.id : attending.code
            ),
            event: event,
        }).unwrap()
        refetch()
    }

    return (
        <div className="container mx-auto mt-2 ">
            <div className="mx-auto w-fit text-center">
                <h1 className="text-4xl font-bold">
                    {locale.attendanceSheet} - {event?.name}
                </h1>
                <div className="mt-4">
                    <ul className="mb-3 border">
                        {participants?.map((attending) => (
                            <li
                                key={attending.name}
                                className={`col- mx-2 my-2 grid max-w-lg justify-center gap-4 align-middle ${
                                    user && isOperator(user)
                                        ? 'grid-cols-7'
                                        : 'grid-cols-6'
                                }`}
                            >
                                <span className="col-span-4 mx-4">
                                    {isAttenderUser(attending)
                                        ? reverseNameOrder(attending.name)
                                        : attending.name}{' '}
                                    {isAttenderUser(attending) &&
                                        attending.ejg_class}
                                </span>
                                <Form.Check
                                    defaultChecked={attending.pivot.is_present}
                                    onClick={toggle(attending)}
                                    disabled={
                                        isLoading || isParticipantsFetching
                                    }
                                    className="col-span-1 h-5 w-5"
                                />
                                {user && isOperator(user) && (
                                    <Button
                                        variant="danger"
                                        onClick={() =>
                                            deleteAttendanceAction(attending)
                                        }
                                        className="col-span-2"
                                    >
                                        {locale.delete}
                                    </Button>
                                )}
                            </li>
                        ))}
                    </ul>
                    <Button onClick={() => refetch()}>{locale.refresh}</Button>
                </div>
            </div>
        </div>
    )
}

export default gated(AttendancePage, isTeacher)
