import { useCallback } from 'react'
import { useNavigate } from 'react-router-dom'

import { CRUDInterface, OptionalFields } from 'types/misc'
import { Slot } from 'types/models'

import adminAPI from 'lib/api/adminAPI'
import Locale from 'lib/locale'

import SlotForm from 'components/Forms/SlotForm'

export type SlotFormValues = OptionalFields<Omit<Slot, 'events'>, 'id'>

const locale = Locale({
    hu: {
        create: 'Sáv létrehozása',
        update: 'Sáv szerkesztése',
    },
    en: {
        create: 'Create slot',
        update: 'Edit slot',
    },
})

const SlotCreator = () => {
    const [createSlot] = adminAPI.useCreateSlotMutation()
    const navigate = useNavigate()
    const onSubmit = useCallback(
        async (values: SlotFormValues) => {
            await createSlot(values)
            navigate('/admin/sav')
        },
        [createSlot, navigate]
    )
    return (
        <SlotForm
            initialValues={{
                name: '',
                slot_type: 'Programsáv',
                starts_at: '',
                ends_at: '',
            }}
            onSubmit={onSubmit}
            submitLabel={locale.create}
        />
    )
}

const SlotUpdator = ({ value: slot }: { value: SlotFormValues }) => {
    const navigate = useNavigate()
    const [updateSlot] = adminAPI.useUpdateSlotMutation()
    const onSubmit = useCallback(
        async (values: SlotFormValues) => {
            await updateSlot(values as Slot)
            navigate('/admin/sav')
        },
        [updateSlot, navigate]
    )
    return (
        <SlotForm
            initialValues={slot}
            onSubmit={onSubmit}
            submitLabel={locale.update}
            enableReinitialize={true}
        />
    )
}

const SlotCRUD: CRUDInterface<Slot, SlotFormValues> = {
    Creator: SlotCreator,
    Updater: SlotUpdator,
}
export default SlotCRUD
