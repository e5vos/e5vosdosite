import useDelayedSet from "hooks/useDelayedSet";
import { useState } from "react";

import { User } from "types/models";

import baseAPI from "lib/api";

import Form from "components/UIKit/Form";

const UserSearchCombobox = ({
    onChange,
}: {
    onChange: (value: User) => any;
}) => {
    const [search, setSearch] = useState("");
    const { data: options } = baseAPI.useSearchUsersQuery(search);

    const onQueryChange = useDelayedSet(setSearch);
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
