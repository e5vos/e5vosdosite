import useDelay from "hooks/useDelayed";
import { useState } from "react";

import { User } from "types/models";

import baseAPI from "lib/api";

import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const elementName = (e: User) => `${e.name} - ${e.ejg_class}`;

const UserSearchCombobox = ({
    onChange,
}: {
    onChange: (value: User) => any;
}) => {
    const [search, setSearch] = useState("");
    const { data: options } = baseAPI.useUserSearchQuery(search);

    const onQueryChange = useDelay((value: string) => {
        if (
            options?.some((e) =>
                elementName(e)
                    .toLocaleLowerCase()
                    .includes(value.toLocaleLowerCase()),
            )
        ) {
            setSearch(value);
        }
    }, 500);

    if (!options) return <Loader />;
    return (
        <div className="max-w-sm">
            <Form.ComboBox
                options={options ?? []}
                onQueryChange={onQueryChange}
                getElementName={elementName}
                onChange={(e) => {
                    if (!e) return;
                    onChange(e);
                }}
                renderElement={(e) => (
                    <span className="mt-3">{elementName(e)}</span>
                )}
                filter={(s) => (e) =>
                    elementName(e)
                        .toLocaleLowerCase()
                        .startsWith(s.toLowerCase())
                }
            />
        </div>
    );
};

export default UserSearchCombobox;
