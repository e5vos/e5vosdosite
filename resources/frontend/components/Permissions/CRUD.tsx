import useConfirm from "hooks/useConfirm";
import useUser from "hooks/useUser";
import { useCallback } from "react";

import { CRUDFormImpl, CRUDInterface } from "types/misc";
import { Permission, PermissionCode, PermissionCodeType } from "types/models";

import adminAPI from "lib/api/adminAPI";
import { isAdmin, isOrganiser } from "lib/gates";
import Locale from "lib/locale";

import PermissionForm from "components/Forms/PermissionForm";
import Dialog from "components/UIKit/Dialog";

const locale = Locale({
    hu: {
        confirm: "Biztosan törölni szeretnéd a jogosultságot?",
    },
    en: {
        confirm: "Are you sure you want to delete this permission?",
    },
});

const getPermissionColor = (type: PermissionCodeType) => {
    switch (type) {
        case PermissionCode.admin:
            return "bg-red-400";
        case PermissionCode.teacher:
            return "bg-yellow-400";
        case PermissionCode.operator:
            return "bg-purple-400";
        case PermissionCode.organiser:
            return "bg-blue-400";
        default:
            return "bg-gray-400";
    }
};

const confirmDialog = () => <Dialog title={locale.confirm} closable={false} />;

const PermissionBubble = ({ value }: CRUDFormImpl<Permission>) => {
    const [onDelete] = adminAPI.useDeletePermissionMutation();
    const [WrappedCofirmDialog, confirmDelete] = useConfirm(confirmDialog);
    const { user } = useUser(false);

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
                className={`${getPermissionColor(value.code)}`}
                onClick={handleClick}
            >
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
