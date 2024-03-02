import { useFormik } from "formik";
import useUser from "hooks/useUser";

import { CRUDForm } from "types/misc";
import {
    Event,
    Permission,
    PermissionCode,
    PermissionCodeType,
    User,
} from "types/models";

import { isAdmin } from "lib/gates";
import Locale from "lib/locale";

import EventSearchCombobox from "components/Event/EventSearch";
import Button from "components/UIKit/Button";
import Form from "components/UIKit/Form";
import UserSearchCombobox from "components/User/UserSearch";

const locale = Locale({
    hu: {
        required: "Kötelező mező",
        threeChars: "Pontosan 3 karakter",
        event: "Esemény",
        code: "Jog",
        submit: "Mentés",
        delete: "Törlés",
        user: "Felhasználó",
        permissionName: (p: PermissionCodeType): string => {
            switch (p) {
                case PermissionCode.admin:
                    return "Adminisztrátor";
                case PermissionCode.organiser:
                    return "Programszervező";
                case PermissionCode.scanner:
                    return "Jelenlétellenőr";
                case PermissionCode.student:
                    return "Diák";
                case PermissionCode.teacher:
                    return "Tanár";
                case PermissionCode.teacheradmin:
                    return "Tanár Adminisztrátor";
                case PermissionCode.operator:
                    return "Zoland";
            }
        },
    },
    en: {
        required: "Required",
        threeChars: "Exactly 3 characters",
        event: "Event",
        code: "Permission",
        submit: "Submit",
        delete: "Delete",
        user: "User",
        permissionName: (p: PermissionCodeType) => {
            switch (p) {
                case PermissionCode.admin:
                    return "Admin";
                case PermissionCode.organiser:
                    return "Organiser";
                case PermissionCode.scanner:
                    return "Scanner";
                case PermissionCode.student:
                    return "Student";
                case PermissionCode.teacher:
                    return "Teacher";
                case PermissionCode.teacheradmin:
                    return "Teacher Admin";
                case PermissionCode.operator:
                    return "Zoland";
            }
        },
    },
});

const PermissionForm = ({
    initialValues,
    onSubmit,
    enableReinitialize,
    resetOnSubmit,
    submitLabel = locale.submit,
    ...rest
}: CRUDForm<Permission>) => {
    const formik = useFormik({
        initialValues: initialValues,
        onSubmit: (values) => {
            const val = onSubmit(values);
            if (resetOnSubmit) formik.resetForm();
            return val;
        },
        enableReinitialize: enableReinitialize,
    });

    return (
        <Form onSubmit={formik.handleSubmit}>
            <Form.Group>
                <Form.Label>{locale.user}</Form.Label>
                <UserSearchCombobox
                    className="!rounded-r-lg"
                    onChange={(u) => formik.setFieldValue("user_id", u.id)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.event}</Form.Label>
                <EventSearchCombobox
                    onChange={(e) => formik.setFieldValue("event_id", e.id)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.code}</Form.Label>
                <Form.Select
                    name="code"
                    defaultValue={initialValues.code}
                    onChange={(e) => {
                        formik.setFieldValue("code", e.target.value);
                    }}
                >
                    {Object.entries(PermissionCode).map((entry) => (
                        <option value={entry[1]} key={entry[1]}>
                            {locale.permissionName(entry[1])}
                        </option>
                    ))}
                </Form.Select>
            </Form.Group>
            <Form.Group>
                <Button type="submit">{submitLabel}</Button>
            </Form.Group>
        </Form>
    );
};

export const UserPermissionCreateForm = ({
    user,
    initialValues,
    onSubmit,
    enableReinitialize,
    resetOnSubmit,
    submitLabel = locale.submit,
    ...rest
}: CRUDForm<Permission> & { user: Pick<User, "id"> }) => {
    const formik = useFormik({
        initialValues: initialValues,
        onSubmit: (values) => {
            const val = onSubmit({ ...values, user_id: user.id });
            if (resetOnSubmit) formik.resetForm();
            return val;
        },
        enableReinitialize: enableReinitialize,
    });

    return (
        <Form onSubmit={formik.handleSubmit}>
            <Form.Group>
                <Form.Label>{locale.event}</Form.Label>
                <EventSearchCombobox
                    onChange={(e) => formik.setFieldValue("event_id", e.id)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.code}</Form.Label>
                <Form.Select
                    name="code"
                    defaultValue={initialValues.code}
                    onChange={(e) =>
                        formik.setFieldValue("code", e.target.value)
                    }
                >
                    {Object.entries(PermissionCode).map((entry) => {
                        return (
                            <option value={entry[1]} key={entry[1]}>
                                {locale.permissionName(entry[1])}
                            </option>
                        );
                    })}
                </Form.Select>
            </Form.Group>
            <Form.Group>
                <Button type="submit">{submitLabel}</Button>
            </Form.Group>
        </Form>
    );
};

export const EventPermissionCreateForm = ({
    event,
    initialValues,
    onSubmit,
    enableReinitialize,
    resetOnSubmit,
    submitLabel = locale.submit,
    ...rest
}: CRUDForm<Permission> & { event: Pick<Event, "id"> }) => {
    const { user } = useUser(false);
    const formik = useFormik({
        initialValues: initialValues,

        onSubmit: (values) => {
            const val = onSubmit({ ...values, event_id: event.id });
            if (resetOnSubmit) formik.resetForm();
            return val;
        },
        enableReinitialize: enableReinitialize,
    });
    const availablePermissions = isAdmin(user)
        ? [PermissionCode.organiser, PermissionCode.scanner]
        : [PermissionCode.scanner];
    return (
        <Form onSubmit={formik.handleSubmit}>
            <Form.Group>
                <Form.Label>{locale.user}</Form.Label>
                <UserSearchCombobox
                    onChange={(u) => formik.setFieldValue("user_id", u.id)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Label>{locale.code}</Form.Label>
                <Form.Select
                    name="code"
                    defaultValue={initialValues.code}
                    onChange={(e) =>
                        formik.setFieldValue("code", e.target.value)
                    }
                >
                    {availablePermissions.map((entry) => {
                        return (
                            <option value={entry} key={entry}>
                                {locale.permissionName(entry)}
                            </option>
                        );
                    })}
                </Form.Select>
            </Form.Group>
            <Form.Group>
                <Button type="submit">{submitLabel}</Button>
            </Form.Group>
        </Form>
    );
};

export default PermissionForm;
