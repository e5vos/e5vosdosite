import { useState } from "react";

import { RequiredFields } from "types/misc";
import { User } from "types/models";

import baseAPI from "lib/api";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import Error from "components/Error";
import { gated } from "components/Gate";
import PermissionCRUD from "components/Permissions/CRUD";
import Loader from "components/UIKit/Loader";
import UserSearchCombobox from "components/User/UserSearch";

const locale = Locale({
    hu: {
        title: "Jogosultságok",
    },
    en: {
        title: "Permissions",
    },
});

const PermissionView = ({
    selectedUser,
}: {
    selectedUser: RequiredFields<User, "permissions">;
}) => {
    return (
        <div>
            <h2 className="text-center">
                {selectedUser.name} - {selectedUser.ejg_class}
            </h2>
            <div>
                {selectedUser.permissions.map((perm) => (
                    <div key={`${perm.event_id}${perm.user_id}`}>
                        <PermissionCRUD.Updater value={perm} />
                    </div>
                ))}
                <PermissionCRUD.Creator value={{ user_id: selectedUser.id }} />
            </div>
        </div>
    );
};

const PermissionsPage = () => {
    const [userID, setUserID] = useState(-1);

    const {
        data: selecteduser,
        isLoading,
        error,
    } = baseAPI.useGetUserQuery({ id: userID });

    return (
        <div>
            <div>
                <h1>{locale.title}</h1>
            </div>
            <div>
                <UserSearchCombobox
                    onChange={(u) => {
                        setUserID(u.id);
                    }}
                />
            </div>
            {userID === -1 && <div>Pls select</div>}
            {userID !== -1 && isLoading && <Loader />}
            {userID !== -1 && error && <Error code={400} />}
            {selecteduser && <PermissionView user={selecteduser} />}
        </div>
    );
};

export default gated(PermissionsPage, isAdmin);
