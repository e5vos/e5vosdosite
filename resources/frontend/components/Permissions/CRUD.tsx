import { CRUDFormImpl, CRUDInterface } from "types/misc";
import { Permission } from "types/models";

import adminAPI from "lib/api/adminAPI";

import PermissionForm from "components/Forms/PermissionForm";

const PermissionFormImpl = ({
    value,
}: CRUDFormImpl<Permission, Permission>) => {
    const [onDelete] = adminAPI.useDeletePermissionMutation();
    const [onSubmit] = adminAPI.useUpdatePermissionMutation();
    return (
        <PermissionForm
            initialValues={value}
            onSubmit={async (perm) => {
                await onSubmit(perm);
            }}
            onDelete={async (perm) => {
                await onDelete(perm);
            }}
        />
    );
};

const PermissionBubble = ({ value }: CRUDFormImpl<Permission>) => {
    return <span>{value.code}</span>;
};

const PermissionFormCreate = ({
    value,
}: CRUDFormImpl<Permission, Partial<Permission>>) => {
    const [onSubmit] = adminAPI.useCreatePermissionMutation();
    return (
        <PermissionForm
            initialValues={{
                code: value.code ?? "",
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
    Updater: PermissionFormImpl,
    Deleter: PermissionFormImpl,
};

export default PermissionCRUD;
