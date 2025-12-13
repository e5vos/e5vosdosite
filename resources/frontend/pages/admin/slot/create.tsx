import { useCallback } from 'react'
import { useNavigate } from 'react-router'

import adminAPI from 'lib/api/adminAPI'
import { isAdmin } from 'lib/gates'
import Locale from 'lib/locale'

import SlotForm from 'components/Forms/SlotForm'
import { gated } from 'components/Gate'
import { SlotFormValues } from 'components/Slot/CRUD'

const locale = Locale({
    hu: {
        submit: 'Létrehozás',
    },
    en: {
        submit: 'Create',
    },
})

const initialValues: SlotFormValues = {
    name: '',
    slot_type: 'Programsáv',
    starts_at: '',
    ends_at: '',
}

const SlotsCreatePage = () => {
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
            initialValues={initialValues}
            onSubmit={onSubmit}
            submitLabel={locale.submit}
        />
    )
}

export default gated(SlotsCreatePage, isAdmin)
