import useDelay from "hooks/useDelayed";
import { useEffect, useState } from "react";

import { UserStub } from "types/models";

import baseAPI from "lib/api";

import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const elementName = (e: UserStub) => `${e.name} - ${e.ejg_class}`;

const filter = (s: String) => (e: UserStub) =>
    elementName(e).toLocaleLowerCase().startsWith(s.toLowerCase());

const UserSearchCombobox = ({
    onChange,
    initialValue,
}: {
    onChange: (value: UserStub) => any;
    initialValue?: UserStub;
}) => {
    const [apisearch, { data, isFetching }] = baseAPI.useLazyUserSearchQuery();
    const [options, setOptions] = useState<UserStub[]>();

    const onQueryChange = useDelay((value: string) => {
        if (
            options?.some((e) =>
                elementName(e)
                    .toLocaleLowerCase()
                    .includes(value.toLocaleLowerCase()),
            )
        ) {
            if (isFetching) return;
            if (!data) {
                apisearch(value);
            } else {
                setOptions((state) => state?.filter(filter(value)) ?? data);
            }
        }
    }, 500);

    useEffect(() => {
        if (data && !options) setOptions(data);
    }, [data, options]);

    if (!options) return <Loader />;
    return (
        <div className="max-w-sm">
            <Form.ComboBox
                options={options ?? []}
                initialValue={initialValue ? elementName(initialValue) : ""}
                onQueryChange={onQueryChange}
                getElementName={elementName}
                onChange={(e) => {
                    if (!e) return;
                    onChange(e);
                }}
                renderElement={(e) => (
                    <span className="mt-3">{elementName(e)}</span>
                )}
                filter={filter}
            />
        </div>
    );
};

export default UserSearchCombobox;
