import { isAdmin } from 'lib/gates'
import Locale from 'lib/locale'

import EventCRUD from 'components/Event/CRUD'
import { gated } from 'components/Gate'

const locale = Locale({
    hu: {
        title: 'Esemény létrehozása',
    },
    en: {
        title: 'Create event',
    },
})

const CreateEventPage = () => {
    return (
        <div className="container mx-auto">
            <h1 className="text-center text-4xl font-bold">{locale.title}</h1>
            <EventCRUD.Creator value={{}} />
        </div>
    )
}

export default gated(CreateEventPage, isAdmin)
