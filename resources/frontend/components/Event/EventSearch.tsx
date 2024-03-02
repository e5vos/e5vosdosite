import useDelay from 'hooks/useDelayed'
import { useState } from 'react'

import { EventStub } from 'types/models'

import eventAPI from 'lib/api/eventAPI'

import Form from 'components/UIKit/Form'

const filter = (s: string) => (e: EventStub) =>
    e.name.toLocaleLowerCase().startsWith(s.toLocaleLowerCase())

const EventSearchCombobox = ({
    onChange,
    initialValue,
}: {
    onChange: (value: EventStub) => any
    initialValue?: EventStub
}) => {
    const [search, setSearch] = useState<string>('-1')

    const { data: options } = eventAPI.useEventSearchQuery(search)

    const onQueryChange = useDelay((value: string) => {
        if (!value.startsWith(search)) {
            setSearch(value)
        }
    })

    return (
        <div className="max-w-sm">
            <Form.ComboBox
                options={options ?? []}
                onQueryChange={onQueryChange}
                initialValue={initialValue ? initialValue.name : ''}
                onChange={(e) => {
                    if (!e) return
                    onChange(e)
                }}
                className="!mb-0 !border-b-0"
                getElementName={(e) => e.name}
                renderElement={(e) => <span>{e.name}</span>}
                filter={filter}
            />
        </div>
    )
}

export default EventSearchCombobox
