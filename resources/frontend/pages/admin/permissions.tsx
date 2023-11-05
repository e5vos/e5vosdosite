import { useState } from "react";

import baseAPI from "lib/api";
import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import Error from "components/Error";
import { gated } from "components/Gate";
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

const PermissionsPage = () => {
    const [userID, setUserID] = useState(-1);

    const {
        data: selecteduser,
        isLoading,
        error,
    } = baseAPI.useGetUserQuery(userID);

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
            {selecteduser && <PermisssionView user={selecteduser[0]} />}
        </div>
    );
};

export default gated(PermissionsPage, isAdmin);
