import { useFormik } from "formik";
import * as Yup from "yup";

import { CRUDForm } from "types/misc";
import { Permission, PermissionCode, PermissionCodeType } from "types/models";

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
        code: "Kód",
        submit: "Mentés",
        delete: "Törlés",
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
        code: "Code",
        submit: "Submit",
        delete: "Delete",
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
    onDelete,
    ...rest
}: CRUDForm<Permission> & { onDelete?: (p: Permission) => void }) => {
    const formik = useFormik({
        initialValues: initialValues,
        validationSchema: Yup.object({
            event_id: Yup.number(),
            code: Yup.string()
                .length(3, locale.threeChars)
                .required(locale.required),
        }),
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
                <Form.Label></Form.Label>
                <UserSearchCombobox
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
                <Form.Select>
                    {Object.entries(PermissionCode).map((entry) => {
                        console.log("entry", entry);
                        return <></>;
                    })}
                </Form.Select>
            </Form.Group>
            <Form.Group>
                <Button type="submit">{submitLabel}</Button>í
                {onDelete && (
                    <Button
                        variant="danger"
                        onClick={() => onDelete(formik.values)}
                    >
                        {locale.delete}
                    </Button>
                )}
            </Form.Group>
        </Form>
    );
};

export default PermissionForm;
