import useDelay from "hooks/useDelayed";
import { useEffect, useState } from "react";

import { UserStub } from "types/models";

import baseAPI from "lib/api";

import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const elementName = (e: UserStub) => `${e.name} - ${e.ejg_class}`;

const filter = (s: String) => (e: UserStub) =>
    elementName(e).toLocaleLowerCase().includes(s.toLocaleLowerCase());

const UserSearchCombobox = ({
    onChange,
    initialValue,
}: {
    onChange: (value: UserStub) => any;
    initialValue?: UserStub;
}) => {
    const [search, setSearch] = useState<string>("-1");

    const { data: options } = baseAPI.useUserSearchQuery(search ?? "-1");

    const onQueryChange = useDelay((value: string) => {
        if (!value.startsWith(search)) {
            setSearch(value);
        }
    }, 500);

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
