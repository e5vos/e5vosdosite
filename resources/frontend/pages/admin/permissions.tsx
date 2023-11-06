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
        userspermissions: "jogosultságai",
        new: "Új jogosultság",
    },
    en: {
        title: "Permissions",
        userspermissions: "permissions",
        new: "New permission",
    },
});

const PermissionView = ({
    user,
}: {
    user: RequiredFields<User, "permissions">;
}) => {
    return (
        <div className="mx-auto max-w-lg">
            <h2 className="font-2xl my-4 font-bold underline">
                {user.name} - {user.ejg_class} {locale.userspermissions}
            </h2>
            <div>
                <div className="mx-auto flex w-fit flex-row gap-3">
                    {user.permissions.map((perm) => (
                        <div
                            key={`${perm.event_id}${perm.user_id}${perm.code}`}
                        >
                            <PermissionCRUD.Updater value={perm} />
                        </div>
                    ))}
                </div>
            </div>
            <hr className="my-5" />
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
        <div className="mx-auto max-w-lg text-center">
            <div>
                <h1 className="mb-10 text-4xl ">{locale.title}</h1>
            </div>
            <div>
                <UserSearchCombobox
                    onChange={(u) => {
                        setUserID(u.id);
                    }}
                />
            </div>
            {userID !== -1 && isLoading && <Loader />}
            {userID !== -1 && error && <Error code={400} />}
            {selecteduser && <PermissionView user={selecteduser} />}
            <div className="mt-10 text-left">
                <h2 className="text-lg italic">{locale.new}</h2>
                <PermissionCRUD.Creator value={{}} />
            </div>
        </div>
    );
};

export default gated(PermissionsPage, isAdmin);
