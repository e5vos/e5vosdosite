import { useCallback, useState } from "react";

import { User } from "types/models";

import baseAPI from "lib/api";

import Form from "components/UIKit/Form";

const UserSearchCombobox = ({
    onChange,
}: {
    onChange: (value: User) => any;
}) => {
    const [search, setSearch] = useState("");
    const [searchTimeout, setSearchTimeout] = useState<number | undefined>();
    const { data: options } = baseAPI.useSearchUsersQuery(search);

    const onQueryChange = useCallback(
        (e: any) => {
            clearTimeout(searchTimeout);
            setSearchTimeout(
                window.setTimeout(() => {
                    console.log(e);
                    setSearch(e);
                }, 500),
            );
        },
        [searchTimeout],
    );
    return (
        <Form.ComboBox
            className="col-span-2"
            options={options ?? []}
            onQueryChange={onQueryChange}
            renderElement={(e) => (
                <span>
                    {e.name} ({e.ejg_class})
                </span>
            )}
        />
    );
};

export default UserSearchCombobox;
