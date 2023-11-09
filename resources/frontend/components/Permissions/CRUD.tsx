import useConfirm, { ConfirmDialogProps } from "hooks/useConfirm";
import useUser from "hooks/useUser";

import { CRUDFormImpl, CRUDInterface } from "types/misc";
import {
    Event,
    Permission,
    PermissionCode,
    PermissionCodeType,
    User,
} from "types/models";

import adminAPI from "lib/api/adminAPI";
import eventAPI from "lib/api/eventAPI";
import { isAdmin, isOrganiser } from "lib/gates";
import Locale from "lib/locale";

import PermissionForm, {
    EventPermissionCreateForm,
    UserPermissionCreateForm,
} from "components/Forms/PermissionForm";
import Button from "components/UIKit/Button";
import Dialog from "components/UIKit/Dialog";

const locale = Locale({
    hu: {
        confirm: "Biztosan törölni szeretnéd a jogosultságot?",
        sure: "Igen, törlés",
        no: "Mégse",
    },
    en: {
        confirm: "Are you sure you want to delete this permission?",
        sure: "Yes, delete permission",
        no: "No",
    },
});

const getPermissionColor = (type: PermissionCodeType) => {
    switch (type) {
        case PermissionCode.admin:
            return "bg-red-400 hover:bg-red-300";
        case PermissionCode.teacher:
            return "bg-yellow-800 hover:bg-yellow-700";
        case PermissionCode.operator:
            return "bg-purple-400 hover:bg-purple-300";
        case PermissionCode.organiser:
            return "bg-blue-400 hover:bg-blue-300";
        default:
            return "bg-gray-400 hover:bg-gray-300";
    }
};

const confirmDialog = ({ handleConfirm, handleCancel }: ConfirmDialogProps) => (
    <Dialog title={locale.confirm} closable={false}>
        <div className="mt-3">
            <Button variant="success" onClick={handleConfirm} className="mr-3">
                {locale.sure}
            </Button>
            <Button variant="danger" onClick={handleCancel}>
                {locale.no}
            </Button>
        </div>
    </Dialog>
);

const PermissionBubble = ({ value }: CRUDFormImpl<Permission>) => {
    const [onDelete] = adminAPI.useDeletePermissionMutation();
    const [WrappedCofirmDialog, confirmDelete] = useConfirm(confirmDialog);
    const { user } = useUser(false);

    const { data: event } = eventAPI.useGetEventQuery({
        id: value.event_id ?? -1,
    });

    const canDelete =
        isAdmin(user) ||
        (value.event_id && isOrganiser({ id: value.event_id })(user));

    const handleClick = async () => {
        if (!canDelete) return;
        if (await confirmDelete()) await onDelete(value);
    };

    return (
        <>
            <WrappedCofirmDialog />
            <span
                className={`rounded-md p-1 ${getPermissionColor(value.code)}`}
                onClick={handleClick}
            >
                {event ? `${event.name}:` : ""}
                {value.code}
            </span>
        </>
    );
};

const PermissionFormCreate = ({
    value,
}: CRUDFormImpl<Permission, Partial<Permission>>) => {
    const [onSubmit] = adminAPI.useCreatePermissionMutation();
    return (
        <PermissionForm
            initialValues={{
                code: value.code ?? PermissionCode.student,
                event_id: value.event_id ?? -1,
                user_id: value.user_id ?? -1,
            }}
            onSubmit={async (perm) => {
                await onSubmit(perm);
            }}
            resetOnSubmit={true}
        />
    );
};

export const EventPermissionCreateFormImpl = ({
    event,
    onSuccess,
}: {
    event: Pick<Event, "id">;
    onSuccess?: (perm: Permission) => void;
}) => {
    const [createPermission] = adminAPI.useCreatePermissionMutation();
    return (
        <EventPermissionCreateForm
            event={event}
            initialValues={{
                code: PermissionCode.scanner,
                event_id: event.id,
                user_id: -1,
            }}
            onSubmit={async (perm) => {
                await createPermission(perm);
                if (onSuccess) onSuccess(perm);
            }}
            resetOnSubmit={true}
        />
    );
};

export const UserPermissionCreateFormImpl = ({
    user,
    onSuccess,
}: {
    user: Pick<User, "id">;
    onSuccess?: (perm: Permission) => void;
}) => {
    const [createPermission] = adminAPI.useCreatePermissionMutation();
    return (
        <UserPermissionCreateForm
            user={user}
            initialValues={{
                code: PermissionCode.student,
                event_id: -1,
                user_id: user.id,
            }}
            onSubmit={async (perm) => {
                await createPermission(perm);
                if (onSuccess) onSuccess(perm);
            }}
        />
    );
};

const PermissionCRUD: CRUDInterface<Permission, Permission> = {
    Creator: PermissionFormCreate,
    Updater: PermissionBubble,
    Deleter: PermissionBubble,
    Reader: PermissionBubble,
};

export default PermissionCRUD;
