import useDelay from "hooks/useDelayed";
import { useEffect, useState } from "react";

import { EventStub } from "types/models";

import eventAPI from "lib/api/eventAPI";

import Form from "components/UIKit/Form";

const filter = (s: string) => (e: EventStub) =>
    e.name.toLocaleLowerCase().startsWith(s.toLocaleLowerCase());

const EventSearchCombobox = ({
    onChange,
    initialValue,
}: {
    onChange: (value: EventStub) => any;
    initialValue?: EventStub;
}) => {
    const [apisearch, { data, isFetching }] =
        eventAPI.useLazyEventSearchQuery();
    const [options, setOptions] = useState<EventStub[]>();

    const onQueryChange = useDelay((value: string) => {
        if (
            options?.some((e) =>
                e.name
                    .toLocaleLowerCase()
                    .includes(value[0].toLocaleLowerCase()),
            )
        ) {
            if (isFetching) return;
            if (!data) {
                apisearch(value);
            } else {
                setOptions((state) => state?.filter(filter(value)) ?? data);
            }
        }
    });

    useEffect(() => {
        if (data && !options) setOptions(data);
    }, [data, options]);

    return (
        <div className="max-w-sm">
            <Form.ComboBox
                options={options ?? []}
                onQueryChange={onQueryChange}
                initialValue={initialValue ? initialValue.name : ""}
                onChange={(e) => {
                    if (!e) return;
                    onChange(e);
                }}
                getElementName={(e) => e.name}
                renderElement={(e) => <span>{e.name}</span>}
                filter={filter}
            />
        </div>
    );
};

export default EventSearchCombobox;
