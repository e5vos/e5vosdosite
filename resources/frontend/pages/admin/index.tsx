import { useState } from "react";
import { useNavigate } from "react-router-dom";

import adminAPI from "lib/api/adminAPI";
import { isOperator } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import Dialog from "components/UIKit/Dialog";

const locale = Locale({
    hu: {
        title: "Eötvös Napok Adminisztrátor",
        system: "Rendszer",
        cacheClear: "Cache törlése",
        UsersDialog: {
            title: "Felhasználók",
            description: "Az összes felhasználó listája",
        },
        eventsDialog: {
            title: "Események",
        },
        slots: {
            title: "Sávok",
            button: "Sávok kezelése",
        },
    },
    en: {
        title: "Eötvös Napok Admin",
        system: "System",
        cacheClear: "Clear cache",
        UsersDialog: {
            title: "Users",
            description: "List of all users",
        },
        eventsDialog: {
            title: "Events",
        },
        slots: {
            title: "Slots",
            button: "Manage slots",
        },
    },
});

const UsersDialog = ({
    open,
    onClose,
}: {
    open: boolean;
    onClose: () => void;
}) => {
    const { data: users } = adminAPI.useGetUsersQuery();

    return (
        <Dialog
            title={locale.UsersDialog.title}
            description={locale.UsersDialog.description}
            onClose={onClose}
            open={open}
        >
            <table>
                {users?.map(
                    (user) =>
                        user.permissions?.map((permission, index) => (
                            <tr
                                key={`${permission.user_id}${permission.event_id}${permission.code}`}
                            >
                                {index === 0 && (
                                    <td rowSpan={user.permissions?.length}>
                                        {user.name}
                                    </td>
                                )}
                                <td>{permission.event_id}</td>
                                <td>{permission.code}</td>
                            </tr>
                        )) ?? (
                            <tr>
                                <td>{user.name}</td>
                            </tr>
                        ),
                )}
            </table>
        </Dialog>
    );
};

const EventsDialog = ({
    open,
    onClose,
}: {
    open: boolean;
    onClose: () => void;
}) => {
    return (
        <Dialog title="Events" open={open} onClose={onClose}>
            Placeholder
        </Dialog>
    );
};

const AdminPage = () => {
    const [clearCache] = adminAPI.useClearCacheMutation();
    const navigate = useNavigate();
    const [usersDialogOpen, setUsersDialogOpen] = useState(false);
    const [eventsDialogOpen, setEventsDialogOpen] = useState(false);
    return (
        <>
            <>
                <UsersDialog
                    open={usersDialogOpen}
                    onClose={() => setUsersDialogOpen(false)}
                />
                <EventsDialog
                    open={eventsDialogOpen}
                    onClose={() => setEventsDialogOpen(false)}
                />
            </>
            <div className="mx-auto max-w-4xl">
                <h1 className="text-center text-4xl font-bold">
                    {locale.title}
                </h1>
                <div className="mt-4 flex gap-64">
                    <div className="">
                        <h3 className="mb-3 text-center text-2xl font-bold">
                            {locale.system}
                        </h3>
                        <Button onClick={() => clearCache()} variant="danger">
                            {locale.cacheClear}
                        </Button>
                    </div>
                    <div className="">
                        <h3 className="mb-3 text-center text-2xl font-bold">
                            {locale.system}
                        </h3>
                        <Button onClick={() => setUsersDialogOpen(true)}>
                            Users
                        </Button>
                        <Button></Button>
                    </div>
                    <div className="">
                        <h3 className="mb-3 text-center text-2xl font-bold">
                            {locale.slots.title}
                        </h3>
                        <Button
                            onClick={() => navigate("/admin/slot/")}
                            variant="info"
                        >
                            {locale.slots.button}
                        </Button>
                    </div>
                </div>
            </div>
        </>
    );
};

export default gated(AdminPage, isOperator);
